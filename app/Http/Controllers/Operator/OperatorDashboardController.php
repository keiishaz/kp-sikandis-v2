<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\KendaraanPemegang;
use Carbon\Carbon;

class OperatorDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Stat utama
        $totalKendaraan = Kendaraan::where('status', 'aktif')->count();

        $pajakHampirHabis = Kendaraan::where('status', 'aktif')
            ->whereNotNull('pajak')
            ->whereDate('pajak', '>=', $now->toDateString())
            ->whereDate('pajak', '<=', $now->copy()->addDays(30)->toDateString())
            ->count();

        $pajakKadaluarsa = Kendaraan::where('status', 'aktif')
            ->whereNotNull('pajak')
            ->whereDate('pajak', '<', $now->toDateString())
            ->count();

        $tanpaPemegang = Kendaraan::where('status', 'aktif')
            ->whereDoesntHave('pemegangs', fn($q) => $q->where('is_active', true))
            ->count();

        // Kendaraan terbaru (5): untuk aksi cepat info
        $kendaraanTerbaru = Kendaraan::with('kategori')
            ->where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Kendaraan dengan pajak hampir habis (daftar detail, ≤30 hari)
        $kendaraanPajakWarning = Kendaraan::with('kategori')
            ->where('status', 'aktif')
            ->whereNotNull('pajak')
            ->whereDate('pajak', '>=', $now->toDateString())
            ->whereDate('pajak', '<=', $now->copy()->addDays(30)->toDateString())
            ->orderBy('pajak', 'asc')
            ->limit(8)
            ->get()
            ->map(function ($k) use ($now) {
                $k->sisa_hari = $now->diffInDays(Carbon::parse($k->pajak), false);
                return $k;
            });

        return view('operator.dashboard', compact(
            'totalKendaraan',
            'pajakHampirHabis',
            'pajakKadaluarsa',
            'tanpaPemegang',
            'kendaraanTerbaru',
            'kendaraanPajakWarning',
        ));
    }
}
