<?php

use App\Http\Controllers\Admin\KelolaOperatorController;
use App\Http\Controllers\Admin\LogViewerController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\SubUnitController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Auth\StepLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\KendaraanPemegangController;
use App\Http\Controllers\KendaraanAktivitasController;
use App\Http\Controllers\QrKendaraanController;
use App\Http\Controllers\PublicKendaraanController;
use App\Models\Unit;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [StepLoginController::class, 'showNikForm'])->name('login');
    Route::post('/login-nik', [StepLoginController::class, 'checkNik'])->name('login.nik');
    Route::get('/login-password', [StepLoginController::class, 'showPasswordForm'])->name('login.password');
    Route::post('/login-password', [StepLoginController::class, 'submitPassword'])->name('login.password.submit');
});

Route::middleware(['auth', 'role:admin,operator'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil Pengguna
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // MENGGABUNGKAN FITUR KENDARAAN (Bisa Diakses Keduanya)
    Route::get('kendaraan/print', [KendaraanController::class, 'print'])->name('kendaraan.print');
    Route::get('kendaraan/print-count', [KendaraanController::class, 'printCount'])->name('kendaraan.print-count');
    Route::resource('kendaraan', KendaraanController::class);
    
    Route::post('kendaraan/{kendaraan}/pemegang', [KendaraanPemegangController::class, 'store'])->name('kendaraan.pemegang.store');
    
    Route::post('kendaraan/{kendaraan}/aktivitas', [KendaraanAktivitasController::class, 'store'])->name('kendaraan.aktivitas.store');
    Route::put('kendaraan-aktivitas/{aktivitas}', [KendaraanAktivitasController::class, 'update'])->name('kendaraan.aktivitas.update');
    Route::delete('kendaraan-aktivitas/{aktivitas}', [KendaraanAktivitasController::class, 'destroy'])->name('kendaraan.aktivitas.destroy');

    // Kategori Kendaraan
    Route::resource('kategori', KategoriController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    
    // QR Kendaraan (Bisa Diakses Keduanya)
    Route::get('qr-kendaraan', [QrKendaraanController::class, 'index'])->name('qr-kendaraan.index');
    Route::post('qr-kendaraan/print', function (\Illuminate\Http\Request $request) {
        $items = json_decode($request->input('items', '[]'), true);
        $itemsJson = json_encode($items);
        return view('qr-kendaraan.print', compact('itemsJson'));
    })->name('qr-kendaraan.print');

    // API endpoints (Shared)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/eksternal-search', function (\Illuminate\Http\Request $request) {
            $unitId = $request->query('unit_id');
            $keyword = $request->query('q');

            $query = \App\Models\Pegawai::with(['unit', 'subUnit']);
            if ($unitId) $query->where('unit_id', $unitId);
            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('nip', 'like', "%{$keyword}%")
                      ->orWhere('nik', 'like', "%{$keyword}%");
                });
            }

            return response()->json($query->take(20)->get()->map(function($p) {
                return [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'nip' => $p->nip,
                    'nik' => $p->nik,
                    'jabatan' => $p->jabatan,
                    'unit' => $p->unit?->nama_unit ?? '-',
                    'sub_unit' => $p->subUnit?->nama_sub_unit ?? '-'
                ];
            }));
        })->name('eksternal.search');

        Route::get('/dummy-pegawai/{nip}', function ($nip, \App\Services\PegawaiInternalService $service) {
            $data = $service->fetchPegawaiByNip($nip);
            if ($data) return response()->json($data);
            return response()->json(['message' => 'Data pegawai internal tidak ditemukan.'], 404);
        })->name('dummy-pegawai.show');
        
        Route::get('pegawai/{pegawai}', function (\App\Models\Pegawai $pegawai) {
            $pegawai->load(['unit', 'subUnit']);
            return response()->json([
                'id'       => $pegawai->id,
                'nama'     => $pegawai->nama,
                'nip'      => $pegawai->nip,
                'jabatan'  => $pegawai->jabatan,
                'unit'     => $pegawai->unit?->nama_unit ?? '-',
                'sub_unit' => $pegawai->subUnit?->nama_sub_unit ?? '-',
            ]);
        })->name('pegawai.show');

        Route::get('units/{unit}/sub-units', function (\App\Models\Unit $unit) {
            return response()->json(
                $unit->subUnits()->orderBy('nama_sub_unit')->get(['id', 'nama_sub_unit'])
            );
        })->name('units.sub-units');
    });

    // ADMIN ONLY Murni (Proteksi Role Terbatas)
    Route::middleware('role:admin')->group(function () {
        // Master Data Eksternal (Admin Only)
        Route::resource('units', UnitController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('units.sub-units', SubUnitController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('pegawai', PegawaiController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Monitoring
        Route::get('/log/aktivitas', [LogViewerController::class, 'aktivitas'])->name('log.aktivitas');
        Route::get('/log/login', [LogViewerController::class, 'login'])->name('log.login');

        // Manajemen Operator
        Route::resource('kelola-operator', KelolaOperatorController::class)
             ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
             ->names('kelola-operator');
    });

});

// Halaman publik kendaraan 
Route::get('/scan/{kode}', [PublicKendaraanController::class, 'show'])
     ->name('kendaraan.publik')
     ->where('kode', '[A-Za-z0-9]{9}');