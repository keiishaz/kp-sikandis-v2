<?php

namespace App\Services;

use App\Contracts\Repositories\KendaraanRepositoryInterface;
use App\Contracts\Repositories\KategoriRepositoryInterface;
use App\Models\Kendaraan;
use App\Models\KendaraanAktivitas;
use App\Models\QrKendaraan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class KendaraanService
{
    public function __construct(
        private readonly KendaraanRepositoryInterface $kendaraanRepo,
        private readonly KategoriRepositoryInterface  $kategoriRepo,
        private readonly QrGeneratorService           $qrGenerator,
    ) {}

    // ─── Index ───────────────────────────────────────────────────────────────

    public function list(array $filters): array
    {
        $paginator = $this->kendaraanRepo->paginate($filters);

        // Decorate the underlying collection items (so modifications persist on the actual objects)
        $this->decoratePajakStatus($paginator->getCollection());

        $counts    = $this->kendaraanRepo->countByStatus();
        $kategoris = $this->kategoriRepo->all();

        // ─── Filter Support (Advanced) ───
        $units = \App\Models\Unit::orderBy('nama_unit')->get();

        return [
            'kendaraans'    => $paginator,
            'status'        => $filters['status'] ?? 'aktif',
            'countAktif'    => $counts['aktif'],
            'countNonaktif' => $counts['nonaktif'],
            'kategoris'     => $kategoris,
            'units'         => $units,
            'filters'       => $filters, 
        ];
    }

    // ─── Print ───────────────────────────────────────────────────────────────

    public function printData(array $filters): array
    {
        $kendaraans = $this->kendaraanRepo->forPrint($filters);
        $this->decoratePajakStatus($kendaraans);

        // Group by OPD for classified report
        $grouped = $kendaraans->groupBy(function($k) {
            return $k->pemegangAktif ? $k->pemegangAktif->display_opd : 'Unit Kerja Lainnya / Belum Ditentukan (Pool-Standby)';
        })->sortKeys();

        return [
            'groupedKendaraans' => $grouped,
            'totalCount'        => $kendaraans->count(),
            'status'            => $filters['status'] ?? 'aktif',
            'filterLabels'      => $this->buildFilterLabels($filters),
        ];
    }

    public function countForPrint(array $filters): int
    {
        return $this->kendaraanRepo->countForPrint($filters);
    }

    // ─── Show ────────────────────────────────────────────────────────────────

    public function detail(Kendaraan $kendaraan): array
    {
        $kendaraan = $this->kendaraanRepo->findWithRelations($kendaraan);
        
        // Inject tax status decoration (label + color)
        $this->decoratePajakStatus([$kendaraan]);

        return [
            'kendaraan' => $kendaraan,
            'pegawais'  => app(\App\Contracts\Repositories\PegawaiRepositoryInterface::class)->allWithRelations(),
        ];
    }

    // ─── Form Support ─────────────────────────────────────────────────────────

    public function formData(): array
    {
        return [
            'kategoris' => $this->kategoriRepo->all(),
            'units'     => \App\Models\Unit::orderBy('type')->orderBy('nama_unit')->get(),
        ];
    }

    // ─── Store ───────────────────────────────────────────────────────────────

    public function store(array $data): Kendaraan
    {
        if ($data['jenis_penggunaan'] === 'jabatan') {
            $data['lokasi_operasional'] = null;
        }

        // Auto-inject unit for operators (cannot differ from their own unit)
        $user = Auth::user();
        if ($user->isOperator() && $user->unit_id) {
            $data['unit_id'] = $user->unit_id;
        }

        $kendaraan = $this->kendaraanRepo->create($data);

        $token = $this->qrGenerator->generateUniqueToken();
        QrKendaraan::create(['kendaraan_id' => $kendaraan->id, 'token' => $token]);

        ActivityLogger::log('TAMBAH KENDARAAN', 'Kendaraan', $kendaraan->id, "Nama Kendaraan: {$kendaraan->nama_kendaraan}");

        KendaraanAktivitas::create([
            'kendaraan_id'      => $kendaraan->id,
            'judul_aktivitas'   => 'Registrasi Kendaraan Baru',
            'deskripsi'         => "Kendaraan {$kendaraan->nama_kendaraan} ({$kendaraan->no_polisi}) telah didaftarkan ke sistem.",
            'tanggal_aktivitas' => now()->toDateString(),
            'created_by'        => Auth::id(),
        ]);

        return $kendaraan;
    }

    // ─── Update ──────────────────────────────────────────────────────────────

    public function update(Kendaraan $kendaraan, array $data): Kendaraan
    {
        if ($data['jenis_penggunaan'] === 'jabatan') {
            $data['lokasi_operasional'] = null;
        }

        $oldNama   = $kendaraan->nama_kendaraan;
        $kendaraan = $this->kendaraanRepo->update($kendaraan, $data);

        ActivityLogger::log('EDIT KENDARAAN', 'Kendaraan', $kendaraan->id, "Dari: {$oldNama} → {$data['nama_kendaraan']}");

        KendaraanAktivitas::create([
            'kendaraan_id'      => $kendaraan->id,
            'judul_aktivitas'   => 'Perubahan Data Kendaraan',
            'deskripsi'         => "Data kendaraan telah diperbarui. Nama sebelumnya: {$oldNama}.",
            'tanggal_aktivitas' => now()->toDateString(),
            'created_by'        => Auth::id(),
        ]);

        return $kendaraan;
    }

    // ─── Toggle Status ───────────────────────────────────────────────────────

    public function toggleStatus(Kendaraan $kendaraan): Kendaraan
    {
        $kendaraan = $this->kendaraanRepo->toggleStatus($kendaraan);

        $newStatus = $kendaraan->status;
        $msg       = $newStatus === 'nonaktif' ? 'dinonaktifkan' : 'diaktifkan';
        $logAction = $newStatus === 'nonaktif' ? 'DEAKTIVASI Kendaraan' : 'AKTIVASI Kendaraan';

        ActivityLogger::log($logAction, 'Kendaraan', $kendaraan->id, $kendaraan->nama_kendaraan);

        KendaraanAktivitas::create([
            'kendaraan_id'      => $kendaraan->id,
            'judul_aktivitas'   => "Status Kendaraan diubah ke {$newStatus}",
            'deskripsi'         => "Kendaraan {$kendaraan->nama_kendaraan} telah {$msg}.",
            'tanggal_aktivitas' => now()->toDateString(),
            'created_by'        => Auth::id(),
        ]);

        return $kendaraan;
    }

    // ─── Internals ───────────────────────────────────────────────────────────

    /**
     * Inject kalkulasi status pajak (label + color) ke setiap item collection/paginator.
     */
    public function decoratePajakStatus(iterable $kendaraans): void
    {
        $now = Carbon::now();

        foreach ($kendaraans as $k) {
            if (! $k->pajak) {
                $k->status_pajak = 'belum_diatur';
                $k->color_pajak  = 'gray';
                continue;
            }

            $pajakDate = Carbon::parse($k->pajak);
            // reset time to 00:00:00 to accurately count full days
            $pajakDateStr = $pajakDate->copy()->startOfDay();
            $nowStr = $now->copy()->startOfDay();

            if ($pajakDateStr->isPast()) {
                $k->status_pajak = 'Telah Jatuh Tempo';
                $k->color_pajak  = 'red';
            } elseif ($nowStr->diffInDays($pajakDateStr, false) <= 60) {
                $k->status_pajak = 'Hampir Jatuh Tempo';
                $k->color_pajak  = 'yellow';
            } else {
                $k->status_pajak = 'Aktif';
                $k->color_pajak  = 'green';
            }
        }
    }

    private function buildFilterLabels(array $filters): array
    {
        $labels = [];

        if (! empty($filters['kategori_id'])) {
            $kat = \App\Models\Kategori::find($filters['kategori_id']);
            if ($kat) {
                $labels['Kategori'] = $kat->nama_kategori;
            }
        }

        if (! empty($filters['jenis_penggunaan'])) {
            $labels['Jenis Penggunaan'] = ucfirst($filters['jenis_penggunaan']);
        }

        $pajakLabels = [
            'aktif'              => 'Aktif',
            'hampir_jatuh_tempo' => 'Hampir Jatuh Tempo',
            'telah_jatuh_tempo'  => 'Telah Jatuh Tempo',
        ];

        if (! empty($filters['status_pajak']) && isset($pajakLabels[$filters['status_pajak']])) {
            $labels['Status Pajak'] = $pajakLabels[$filters['status_pajak']];
        }

        if (! empty($filters['unit_id'])) {
            $unit = \App\Models\Unit::find($filters['unit_id']);
            if ($unit) {
                $labels['Dinas / OPD'] = $unit->nama_unit;
            }
        }

        return $labels;
    }
}
