<?php

namespace App\Repositories;

use App\Contracts\Repositories\DashboardRepositoryInterface;
use App\Models\Kendaraan;
use App\Models\KendaraanPemegang;
use App\Models\Kategori;
use App\Models\Pegawai;
use App\Models\QrKendaraan;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function kendaraanStats(): array
    {
        return [
            'total'        => Kendaraan::count(),
            'aktif'        => Kendaraan::where('status', 'aktif')->count(),
            'tidak_aktif'  => Kendaraan::where('status', '!=', 'aktif')->count(),
            'jabatan'      => Kendaraan::where('jenis_penggunaan', 'jabatan')->count(),
            'operasional'  => Kendaraan::where('jenis_penggunaan', 'operasional')->count(),
        ];
    }

    public function pajakStats(): array
    {
        $today           = Carbon::today();
        $thirtyDaysLater = Carbon::today()->addDays(30);

        return [
            'aktif'        => Kendaraan::whereNotNull('pajak')->where('pajak', '>=', $today->format('Y-m-d'))->count(),
            'mati'         => Kendaraan::whereNotNull('pajak')->where('pajak', '<', $today->format('Y-m-d'))->count(),
            'tidak_ada'    => Kendaraan::whereNull('pajak')->count(),
            'segera'       => Kendaraan::whereNotNull('pajak')
                                ->where('pajak', '>=', $today->format('Y-m-d'))
                                ->where('pajak', '<=', $thirtyDaysLater->format('Y-m-d'))
                                ->count(),
        ];
    }

    public function pemegangStats(int $totalKendaraan): array
    {
        $denganPemegang = KendaraanPemegang::where('is_active', true)->count();

        return [
            'dengan_pemegang'  => $denganPemegang,
            'tanpa_pemegang'   => $totalKendaraan - $denganPemegang,
        ];
    }

    public function qrStats(): array
    {
        return [
            'total' => QrKendaraan::count(),
            'scan'  => QrKendaraan::sum('scan_count'),
            'top'   => QrKendaraan::with('kendaraan')->orderByDesc('scan_count')->limit(5)->get(),
        ];
    }

    public function qrChartData(): array
    {
        $records = QrKendaraan::whereYear('updated_at', date('Y'))
            ->get()
            ->groupBy(fn ($val) => Carbon::parse($val->updated_at)->format('n'));

        $bulan = [];
        $data  = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = Carbon::create()->month($i)->translatedFormat('M');
            $data[]  = isset($records[$i]) ? $records[$i]->sum('scan_count') : 0;
        }

        return ['bulan' => $bulan, 'data' => $data];
    }

    public function masterDataStats(): array
    {
        return [
            'pegawai'  => Pegawai::count(),
            'unit'     => Unit::count(),
            'kategori' => Kategori::count(),
            'operator' => User::whereHas('role', fn ($q) => $q->where('nama_role', 'operator'))->count(),
        ];
    }

    public function distribusiKategori(): Collection
    {
        return Kategori::withCount('kendaraans')->get();
    }

    public function kendaraanTerbaru(int $limit = 6): Collection
    {
        return Kendaraan::with(['kategori', 'pemegangAktif.pegawai'])->latest()->limit($limit)->get();
    }

    public function daftarPajakSegera(int $limit = 5): Collection
    {
        $today           = Carbon::today();
        $thirtyDaysLater = Carbon::today()->addDays(30);

        return Kendaraan::whereNotNull('pajak')
            ->where('pajak', '>=', $today->format('Y-m-d'))
            ->where('pajak', '<=', $thirtyDaysLater->format('Y-m-d'))
            ->with('pemegangAktif.pegawai')
            ->orderBy('pajak', 'asc')
            ->limit($limit)
            ->get();
    }
}
