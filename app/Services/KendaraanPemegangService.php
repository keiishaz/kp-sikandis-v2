<?php

namespace App\Services;

use App\Models\Kendaraan;
use App\Models\KendaraanAktivitas;
use App\Models\KendaraanPemegang;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KendaraanPemegangService
{
    public function __construct(
        private readonly PegawaiInternalService $pegawaiInternalService,
        private readonly FileUploadService $fileUploadService,
    ) {}

    /**
     * Cek apakah kendaraan memiliki pemegang aktif yang belum dikonfirmasi penggantinya.
     */
    public function getActivePemegang(Kendaraan $kendaraan): ?KendaraanPemegang
    {
        return KendaraanPemegang::where('kendaraan_id', $kendaraan->id)
            ->where('is_active', true)
            ->with('pegawai')
            ->first();
    }

    /**
     * Proses assign pemegang baru. Jika ada pemegang lama, lakukan serah terima.
     * Semua operasi dibungkus dalam DB::transaction.
     *
     * @throws ValidationException  Jika Operator mencoba assign pemegang lintas OPD.
     */
    public function assign(array $data, Kendaraan $kendaraan, ?KendaraanPemegang $pemegangLama): void
    {
        DB::transaction(function () use ($data, $kendaraan, $pemegangLama) {
            $holderData = $this->resolveHolderData($data);

            // Upload the Dokumen SK file
            $filePath = $this->fileUploadService->upload($data['dokumen_sk']);
            $data['dokumen_sk'] = $filePath; // replace the UploadedFile with the path string

            // ── OPD Consistency Check (only for API source) ──
            if ($data['source_system'] === 'API' && $kendaraan->unit) {
                $this->validateOpdConsistency($holderData['opd'], $kendaraan, $data);
            }

            if ($pemegangLama) {
                $this->deactivatePemegang($pemegangLama, $kendaraan);
            }

            $this->createPemegang($data, $kendaraan, $holderData);
            $this->logAssignment($data, $kendaraan, $holderData, $pemegangLama);

            // ── Discovery: auto-populate api_mapping_key on first API assignment ──
            if ($data['source_system'] === 'API' && $kendaraan->unit && $holderData['opd']) {
                $this->updateApiMappingKey($kendaraan, $holderData['opd']);
            }
        });
    }

    // ─── Private Helpers ───────────────────────────────────────────────────────────

    /**
     * Validate that the employee's OPD (from API) matches the kendaraan's unit.
     *
     * - Operator: hard block with ValidationException.
     * - Admin: allowed, but the controller layer intercepts cross_opd_warning first.
     */
    private function validateOpdConsistency(string $opdFromApi, Kendaraan $kendaraan, array $data): void
    {
        $unit = $kendaraan->unit;

        // No mapping key yet — nothing to validate against (Discovery phase will set it after success)
        if (! $unit->api_mapping_key) {
            return;
        }

        $isMismatch = strtolower(trim($opdFromApi)) !== strtolower(trim($unit->api_mapping_key));

        if (! $isMismatch) {
            return;
        }

        $user = Auth::user();

        // Operator → hard block
        if ($user->isOperator()) {
            throw ValidationException::withMessages([
                'nip' => "Pegawai ini berasal dari OPD berbeda ({$opdFromApi}). Kendaraan ini terdaftar di unit '{$unit->nama_unit}'. Anda tidak diizinkan melakukan penugasan lintas OPD.",
            ]);
        }

        // Admin → only block if force_replace_opd flag is not set
        // The controller will have already shown the warning and returned early before calling assign()
    }

    /**
     * Auto-discovery: populate api_mapping_key the first time an API assignment is made.
     * Does NOT overwrite an existing key (only Admin can change it via the Units CRUD).
     */
    private function updateApiMappingKey(Kendaraan $kendaraan, string $opdFromApi): void
    {
        if (! $kendaraan->unit->api_mapping_key) {
            $kendaraan->unit->update(['api_mapping_key' => trim($opdFromApi)]);
        }
    }

    /**
     * Resolve nama, nip, jabatan, dan OPD dari sumber Manual atau API.
     * Eliminasi duplikasi kode dari controller lama.
     */
    private function resolveHolderData(array $data): array
    {
        if ($data['source_system'] === 'Manual') {
            $peg = Pegawai::with('unit')->findOrFail($data['pegawai_id']);

            return [
                'nama'    => $peg->nama,
                'nip'     => $peg->nip,
                'jabatan' => $peg->jabatan ?? '',
                'opd'     => $peg->unit?->nama_unit ?? '',
            ];
        }

        // Source: API - Trim NIP to prevent trailing/leading spaces from causing 404
        $nip      = trim($data['nip'] ?? '');
        $internal = $this->pegawaiInternalService->fetchPegawaiByNip($nip);

        $jabatan = $internal['jabatan'] ?? '—';
        if (! empty($internal['pangkat']) && $internal['pangkat'] !== ' ()') {
            $jabatan .= ' | ' . $internal['pangkat'];
        }

        return [
            'nama'    => $internal['nama'] ?? 'Unknown (API)',
            'nip'     => $data['nip'],
            'jabatan' => $jabatan,
            'opd'     => $internal['opd'] ?? '',
        ];
    }

    private function deactivatePemegang(KendaraanPemegang $pemegang, Kendaraan $kendaraan): void
    {
        $pemegang->update(['is_active' => false, 'tanggal_selesai' => now()->toDateString()]);

        $namaLama = $pemegang->nama_pegawai ?? ($pemegang->pegawai?->nama ?? 'Pegawai Internal');
        $nipLama  = $pemegang->nip ?? ($pemegang->pegawai?->nip ?? '-');

        ActivityLogger::log(
            'NONAKTIF PEMEGANG',
            'Kendaraan',
            $kendaraan->id,
            "Kendaraan: {$kendaraan->nama_kendaraan}, Pegawai Dilepas: {$namaLama}, NIP: {$nipLama}"
        );
    }

    private function createPemegang(array $data, Kendaraan $kendaraan, array $holderData): void
    {
        $isManual = $data['source_system'] === 'Manual';

        KendaraanPemegang::create([
            'kendaraan_id'    => $kendaraan->id,
            'source_system'   => $data['source_system'],
            'pegawai_id'      => $isManual ? $data['pegawai_id'] : null,
            'nip'             => $isManual ? null : $data['nip'],
            'nama_pegawai'    => $holderData['nama'],
            'jabatan_pegawai' => $holderData['jabatan'],
            'unit_pegawai'    => $holderData['opd'],
            'dokumen_sk'      => $data['dokumen_sk'],
            'tanggal_sk'      => $data['tanggal_sk'],
            'tanggal_mulai'   => $data['tanggal_mulai'],
            'tanggal_selesai' => null,
            'is_active'       => true,
        ]);
    }

    private function logAssignment(
        array $data,
        Kendaraan $kendaraan,
        array $holderData,
        ?KendaraanPemegang $pemegangLama,
    ): void {
        $isManual = $data['source_system'] === 'Manual';
        $namaBaru = $holderData['nama'];
        $nipBaru  = $holderData['nip'];
        $opdBaru  = $holderData['opd'];

        ActivityLogger::log(
            'TAMBAH PEMEGANG',
            'Kendaraan',
            $kendaraan->id,
            "Kendaraan: {$kendaraan->nama_kendaraan}, Pegawai: {$namaBaru}, NIP: {$nipBaru}, Source: {$data['source_system']}, SK Uploaded"
        );

        if ($pemegangLama) {
            $namaLama = $pemegangLama->nama_pegawai ?? ($pemegangLama->pegawai?->nama ?? 'Pegawai Internal');

            ActivityLogger::log(
                'SERAH TERIMA PEMEGANG',
                'Kendaraan',
                $kendaraan->id,
                "Kendaraan: {$kendaraan->nama_kendaraan}, Dari: {$namaLama} → {$namaBaru}, SK Uploaded"
            );

            $judul      = 'Serah Terima Pemegang Kendaraan';
            $deskripsi  = "Serah terima kendaraan dari {$namaLama} kepada {$namaBaru}. [Dokumen SK Terlampir]";
        } else {
            $judul      = 'Penugasan Pemegang Kendaraan';
            $deskripsi  = "Pegawai {$namaBaru} (NIP: {$nipBaru}) ditugaskan sebagai pemegang kendaraan. [Dokumen SK Terlampir]";
        }

        if (! $isManual && $opdBaru) {
            $deskripsi .= " (OPD: {$opdBaru})";
        }

        KendaraanAktivitas::create([
            'kendaraan_id'      => $kendaraan->id,
            'judul_aktivitas'   => $judul,
            'deskripsi'         => $deskripsi,
            'tanggal_aktivitas' => $data['tanggal_mulai'],
            'created_by'        => Auth::id(),
        ]);
    }
}
