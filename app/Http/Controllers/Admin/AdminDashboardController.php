<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\KendaraanPemegang;
use App\Models\Pegawai;
use App\Models\QrKendaraan;
use App\Models\Unit;
use App\Models\Kategori;
use App\Models\User;
use App\Services\LogReaderService;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(LogReaderService $logService)
    {
        // ─── Statistik Kendaraan ───────────────────────────────────────────
        $totalKendaraan     = Kendaraan::count();
        $kendaraanAktif     = Kendaraan::where('status', 'aktif')->count();
        $kendaraanTidakAktif = Kendaraan::where('status', '!=', 'aktif')->count();

        // Berdasarkan jenis penggunaan
        $kendaraanJabatan     = Kendaraan::where('jenis_penggunaan', 'jabatan')->count();
        $kendaraanOperasional = Kendaraan::where('jenis_penggunaan', 'operasional')->count();

        // Pajak - berdasarkan kolom 'pajak' (tanggal atau status)
        // Kendaraan dengan pajak yang masih berlaku (tanggal > hari ini)
        $today = Carbon::today();
        $thirtyDaysLater = Carbon::today()->addDays(30);

        $pajakAktif    = Kendaraan::whereNotNull('pajak')->where('pajak', '>=', $today->format('Y-m-d'))->count();
        $pajakMati     = Kendaraan::whereNotNull('pajak')->where('pajak', '<', $today->format('Y-m-d'))->count();
        $pajakTidakAda = Kendaraan::whereNull('pajak')->count();
        $pajakSegera  = Kendaraan::whereNotNull('pajak')
                            ->where('pajak', '>=', $today->format('Y-m-d'))
                            ->where('pajak', '<=', $thirtyDaysLater->format('Y-m-d'))
                            ->count();

        // ─── Statistik Pemegang ────────────────────────────────────────────
        $kendaraanDenganPemegang  = KendaraanPemegang::where('is_active', true)->count();
        $kendaraanTanpaPemegang   = $totalKendaraan - $kendaraanDenganPemegang;

        // ─── Statistik QR ─────────────────────────────────────────────────
        $totalQr        = QrKendaraan::count();
        $totalScan      = QrKendaraan::sum('scan_count');
        $topQr          = QrKendaraan::with('kendaraan')
                            ->orderByDesc('scan_count')
                            ->limit(5)
                            ->get();

        // ─── Statistik Master Data ────────────────────────────────────────
        $totalPegawai   = Pegawai::count();
        $totalUnit      = Unit::count();
        $totalKategori  = Kategori::count();
        $totalOperator  = User::whereHas('role', fn($q) => $q->where('nama_role', 'operator'))->count();

        // ─── Distribusi Kendaraan Per Kategori ────────────────────────────
        $distribusiKategori = Kategori::withCount('kendaraans')->get();

        // ─── Kendaraan Terbaru ────────────────────────────────────────────
        $kendaraanTerbaru = Kendaraan::with(['kategori', 'pemegangAktif.pegawai'])
                            ->latest()
                            ->limit(6)
                            ->get();

        // ─── Pajak Hampir Habis (Detail) ──────────────────────────────────
        $daftarPajakSegera = Kendaraan::whereNotNull('pajak')
                            ->where('pajak', '>=', $today->format('Y-m-d'))
                            ->where('pajak', '<=', $thirtyDaysLater->format('Y-m-d'))
                            ->with('pemegangAktif.pegawai')
                            ->orderBy('pajak', 'asc')
                            ->limit(5)
                            ->get();

        // ─── Log Aktivitas Terbaru (dari file log) ────────────────────────
        $recentLogs = collect($logService->readAktivitasLog(null, null, 8, 1)->items());

        return view('admin.dashboard', compact(
            'totalKendaraan',
            'kendaraanAktif',
            'kendaraanTidakAktif',
            'kendaraanJabatan',
            'kendaraanOperasional',
            'pajakAktif',
            'pajakMati',
            'pajakTidakAda',
            'pajakSegera',
            'kendaraanDenganPemegang',
            'kendaraanTanpaPemegang',
            'totalQr',
            'totalScan',
            'topQr',
            'totalPegawai',
            'totalUnit',
            'totalKategori',
            'totalOperator',
            'distribusiKategori',
            'kendaraanTerbaru',
            'daftarPajakSegera',
            'recentLogs',
        ));
    }
}
