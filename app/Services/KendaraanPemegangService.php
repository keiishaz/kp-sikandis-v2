<?php

namespace App\Services;

use App\Models\Kendaraan;
use App\Models\KendaraanAktivitas;
use App\Models\KendaraanPemegang;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KendaraanPemegangService
{
    public function __construct(
        private readonly PegawaiInternalService $pegawaiInternalService,
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
     */
    public function assign(array $data, Kendaraan $kendaraan, ?KendaraanPemegang $pemegangLama): void
    {
        DB::transaction(function () use ($data, $kendaraan, $pemegangLama) {
            $holderData = $this->resolveHolderData($data);

            if ($pemegangLama) {
                $this->deactivatePemegang($pemegangLama, $kendaraan);
            }

            $this->createPemegang($data, $kendaraan, $holderData);
            $this->logAssignment($data, $kendaraan, $holderData, $pemegangLama);
        });
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

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
            'nomor_sk'        => $data['nomor_sk'],
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
            "Kendaraan: {$kendaraan->nama_kendaraan}, Pegawai: {$namaBaru}, NIP: {$nipBaru}, Source: {$data['source_system']}, SK: {$data['nomor_sk']}"
        );

        if ($pemegangLama) {
            $namaLama = $pemegangLama->nama_pegawai ?? ($pemegangLama->pegawai?->nama ?? 'Pegawai Internal');

            ActivityLogger::log(
                'SERAH TERIMA PEMEGANG',
                'Kendaraan',
                $kendaraan->id,
                "Kendaraan: {$kendaraan->nama_kendaraan}, Dari: {$namaLama} → {$namaBaru}, SK: {$data['nomor_sk']}"
            );

            $judul      = 'Serah Terima Pemegang Kendaraan';
            $deskripsi  = "Serah terima kendaraan dari {$namaLama} kepada {$namaBaru}. SK: {$data['nomor_sk']}.";
        } else {
            $judul      = 'Penugasan Pemegang Kendaraan';
            $deskripsi  = "Pegawai {$namaBaru} (NIP: {$nipBaru}) ditugaskan sebagai pemegang kendaraan. SK: {$data['nomor_sk']}.";
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
