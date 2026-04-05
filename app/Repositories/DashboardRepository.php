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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DashboardRepository implements DashboardRepositoryInterface
{
    // ─── Unit Scope ───────────────────────────────────────────────────────────

    /**
     * Returns a base Kendaraan query scoped to the operator's unit if applicable.
     */
    private function kendaraanBase(): Builder
    {

        $query = Kendaraan::query();
        $user  = auth()->user();

        if ($user && $user->isOperator() && $user->unit_id) {
            $query->where('unit_id', $user->unit_id);
        }

        return $query;
    }

    public function kendaraanStats(): array
    {
        $base = $this->kendaraanBase();

        return [
            'total'        => (clone $base)->count(),
            'aktif'        => (clone $base)->where('status', 'aktif')->count(),
            'tidak_aktif'  => (clone $base)->where('status', '!=', 'aktif')->count(),
            'jabatan'      => (clone $base)->where('jenis_penggunaan', 'jabatan')->count(),
            'operasional'  => (clone $base)->where('jenis_penggunaan', 'operasional')->count(),
        ];
    }

    public function pajakStats(): array
    {
        $today           = Carbon::today();
        $sixtyDaysLater  = Carbon::today()->addDays(60);
        $base            = $this->kendaraanBase();

        return [
            'aktif'     => (clone $base)->whereNotNull('pajak')->where('pajak', '>=', $today->format('Y-m-d'))->count(),
            'mati'      => (clone $base)->whereNotNull('pajak')->where('pajak', '<', $today->format('Y-m-d'))->count(),
            'tidak_ada' => (clone $base)->whereNull('pajak')->count(),
            'segera'    => (clone $base)->whereNotNull('pajak')
                               ->where('pajak', '>=', $today->format('Y-m-d'))
                               ->where('pajak', '<=', $sixtyDaysLater->format('Y-m-d'))
                               ->count(),
        ];
    }

    public function pemegangStats(int $totalKendaraan): array
    {
        $base           = $this->kendaraanBase();
        // Count vehicles in scope that have an active holder
        $denganPemegang = (clone $base)->whereHas('pemegangAktif')->count();

        return [
            'dengan_pemegang' => $denganPemegang,
            'tanpa_pemegang'  => $totalKendaraan - $denganPemegang,
        ];
    }

    public function qrStats(): array
    {
        $user = auth()->user();
        
        $qrQuery = QrKendaraan::whereHas('kendaraan', function ($q) use ($user) {
            if ($user && $user->isOperator() && $user->unit_id) {
                $q->where('unit_id', $user->unit_id);
            }
        });

        return [
            'total' => (clone $qrQuery)->count(),
            'scan'  => (clone $qrQuery)->sum('scan_count'),
            'top'   => (clone $qrQuery)->with('kendaraan')->orderByDesc('scan_count')->limit(5)->get(),
        ];
    }

    public function qrChartData(): array
    {
        $user = auth()->user();

        $records = QrKendaraan::whereHas('kendaraan', function ($q) use ($user) {
                if ($user && $user->isOperator() && $user->unit_id) {
                    $q->where('unit_id', $user->unit_id);
                }
            })
            ->whereYear('updated_at', date('Y'))
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
        $user = auth()->user();
        
        return Kategori::withCount(['kendaraans' => function ($query) use ($user) {
            if ($user && $user->isOperator() && $user->unit_id) {
                $query->where('unit_id', $user->unit_id);
            }
        }])->get();
    }

    public function kendaraanTerbaru(int $limit = 6): Collection
    {
        return $this->kendaraanBase()
            ->with(['kategori', 'pemegangAktif.pegawai'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function daftarPajakSegera(int $limit = 5): Collection
    {
        $today          = Carbon::today();
        $sixtyDaysLater = Carbon::today()->addDays(60);

        return $this->kendaraanBase()
            ->whereNotNull('pajak')
            ->where('pajak', '>=', $today->format('Y-m-d'))
            ->where('pajak', '<=', $sixtyDaysLater->format('Y-m-d'))
            ->with('pemegangAktif.pegawai')
            ->orderBy('pajak', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($k) use ($today) {
                $k->sisa_hari = $today->diffInDays(Carbon::parse($k->pajak), false);
                return $k;
            });
    }
}
