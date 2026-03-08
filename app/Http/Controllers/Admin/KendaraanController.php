<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Kategori;
use App\Models\QrKendaraan;
use App\Http\Requests\Admin\StoreKendaraanRequest;
use App\Http\Requests\Admin\UpdateKendaraanRequest;
use App\Services\QrGeneratorService;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'aktif');
        $query = Kendaraan::with('kategori')
            ->where('status', $status)
            ->orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kendaraan', 'like', "%{$search}%")
                  ->orWhere('no_polisi', 'like', "%{$search}%")
                  ->orWhereHas('kategori', function ($qKat) use ($search) {
                      $qKat->where('nama_kategori', 'like', "%{$search}%");
                  });
            });
        }

        $kendaraans = $query->paginate(10)->withQueryString();

        // Menyematkan perhitungan status pajak untuk setiap kendaraan
        $now = \Carbon\Carbon::now();
        foreach ($kendaraans as $k) {
            if (!$k->pajak) {
                $k->status_pajak = 'belum_diatur';
                $k->color_pajak = 'gray';
            } else {
                $pajakDate = \Carbon\Carbon::parse($k->pajak);
                if ($pajakDate->isPast()) {
                    $k->status_pajak = 'Telah Jatuh Tempo';
                    $k->color_pajak = 'red';
                } else {
                    $diffMonths = $now->diffInMonths($pajakDate, false);
                    if ($diffMonths <= 6) {
                        $k->status_pajak = 'Hampir Jatuh Tempo';
                        $k->color_pajak = 'yellow';
                    } else {
                        $k->status_pajak = 'Aktif';
                        $k->color_pajak = 'green';
                    }
                }
            }
        }
        
        $countAktif = Kendaraan::where('status', 'aktif')->count();
        $countNonaktif = Kendaraan::where('status', 'nonaktif')->count();

        return view('admin.kendaraan.index', compact('kendaraans', 'status', 'countAktif', 'countNonaktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('admin.kendaraan.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKendaraanRequest $request, QrGeneratorService $qrService)
    {
        $validated = $request->validated();

        // Jika jabatan, pastikan lokasi diset null
        if ($validated['jenis_penggunaan'] === 'jabatan') {
            $validated['lokasi_operasional'] = null;
        }

        // 1. Simpan Data Kendaraan
        $kendaraan = Kendaraan::create($validated);

        // 2. Auto-Generate Token QR Unik dan Simpan
        $token = $qrService->generateUniqueToken();
        QrKendaraan::create([
            'kendaraan_id' => $kendaraan->id,
            'token'        => $token,
        ]);

        // 3. Catat Log Aktivitas Menggunakan ActivityLogger Global
        ActivityLogger::log(
            'TAMBAH KENDARAAN',
            'Kendaraan',
            $kendaraan->id,
            "Nama Kendaraan: {$kendaraan->nama_kendaraan}"
        );

        return redirect()->route('admin.kendaraan.index')
                         ->with('success', 'Data Kendaraan berhasil ditambahkan beserta Token QR.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kendaraan $kendaraan)
    {
        $kendaraan->load(['kategori', 'qrKendaraan']);
        return view('admin.kendaraan.show', compact('kendaraan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kendaraan $kendaraan)
    {
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('admin.kendaraan.edit', compact('kendaraan', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKendaraanRequest $request, Kendaraan $kendaraan)
    {
        $validated = $request->validated();

        // Jika diganti menjadi jabatan, maka lokasi harus diset null (dihapus)
        if ($validated['jenis_penggunaan'] === 'jabatan') {
            $validated['lokasi_operasional'] = null;
        }

        $oldNama = $kendaraan->nama_kendaraan;
        $kendaraan->update($validated);

        // Catat Log Aktivitas (Format Edit yang baku) Menggunakan ActivityLogger Global
        ActivityLogger::log(
            'EDIT KENDARAAN',
            'Kendaraan',
            $kendaraan->id,
            "Dari: {$oldNama} → {$validated['nama_kendaraan']}"
        );

        return redirect()->route('admin.kendaraan.index')
                         ->with('success', 'Data Kendaraan berhasil diperbarui.');
    }

    /**
     * Nonaktifkan atau aktifkan status kendaraan (Toggle Status).
     * Sesuai request 'nonaktifkan kendaraan'.
     */
    public function destroy(Kendaraan $kendaraan)
    {
        $newStatus = $kendaraan->status === 'aktif' ? 'nonaktif' : 'aktif';
        $kendaraan->update(['status' => $newStatus]);
        
        $msg = $newStatus === 'nonaktif' ? 'dinonaktifkan' : 'diaktifkan';
        $logAction = $newStatus === 'nonaktif' ? 'DEAKTIVASI Kendaraan' : 'AKTIVASI Kendaraan';

        // Catat Log Aktivitas Menggunakan ActivityLogger Global
        ActivityLogger::log(
            "{$logAction}",
            'Kendaraan',
            $kendaraan->id,
            "{$kendaraan->nama_kendaraan}"
        );

        return redirect()->route('admin.kendaraan.index')
                         ->with('success', "Status kendaraan berhasil {$msg}.");
    }
}
