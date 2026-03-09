<?php

use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KelolaOperatorController;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\KendaraanPemegangController;
use App\Http\Controllers\Admin\LogViewerController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\SubUnitController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Auth\StepLoginController;
use App\Http\Controllers\DashboardController;
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
    Route::get('/login', [StepLoginController::class, 'showNipForm'])->name('login');
    Route::post('/login-nip', [StepLoginController::class, 'checkNip'])->name('login.nip');
    Route::get('/login-password', [StepLoginController::class, 'showPasswordForm'])->name('login.password');
    Route::post('/login-password', [StepLoginController::class, 'submitPassword'])->name('login.password.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil Pengguna
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

        // Modul Logs
        Route::get('/log/aktivitas', [LogViewerController::class, 'aktivitas'])->name('log.aktivitas');
        Route::get('/log/login', [LogViewerController::class, 'login'])->name('log.login');

        Route::resource('kelola-operator', KelolaOperatorController::class)
             ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
             ->names('kelola-operator');

        Route::resource('units', UnitController::class)
             ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        Route::resource('units.sub-units', SubUnitController::class)
             ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // URL: admin/pegawai
        Route::resource('pegawai', PegawaiController::class)
             ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // URL: admin/kategori
        Route::resource('kategori', KategoriController::class)
             ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // URL: admin/kendaraan
        Route::resource('kendaraan', KendaraanController::class);

        // Ganti / assign pemegang kendaraan
        Route::post('kendaraan/{kendaraan}/pemegang', [KendaraanPemegangController::class, 'store'])
             ->name('kendaraan.pemegang.store');

        // CRUD Aktivitas Kendaraan
        Route::post('kendaraan/{kendaraan}/aktivitas', [App\Http\Controllers\Admin\KendaraanAktivitasController::class, 'store'])->name('kendaraan.aktivitas.store');
        Route::put('kendaraan-aktivitas/{aktivitas}', [App\Http\Controllers\Admin\KendaraanAktivitasController::class, 'update'])->name('kendaraan.aktivitas.update');
        Route::delete('kendaraan-aktivitas/{aktivitas}', [App\Http\Controllers\Admin\KendaraanAktivitasController::class, 'destroy'])->name('kendaraan.aktivitas.destroy');

        // QR Kendaraan — Read Only + Print Preview
        Route::get('qr-kendaraan', [App\Http\Controllers\Admin\QrKendaraanController::class, 'index'])->name('qr-kendaraan.index');
        Route::post('qr-kendaraan/print', function (\Illuminate\Http\Request $request) {
            $items = json_decode($request->input('items', '[]'), true);
            $itemsJson = json_encode($items);
            return view('admin.qr-kendaraan.print', compact('itemsJson'));
        })->name('qr-kendaraan.print');

        // API: Preview data pegawai (untuk modal form)
        Route::get('api/pegawai/{pegawai}', function (\App\Models\Pegawai $pegawai) {
            $pegawai->load(['unit', 'subUnit']);
            return response()->json([
                'id'       => $pegawai->id,
                'nama'     => $pegawai->nama,
                'nip'      => $pegawai->nip,
                'jabatan'  => $pegawai->jabatan,
                'unit'     => $pegawai->unit?->nama_unit ?? '-',
                'sub_unit' => $pegawai->subUnit?->nama_sub_unit ?? '-',
            ]);
        })->name('api.pegawai.show');

        // Endpoint dependent dropdown sub-unit berdasarkan unit
        Route::get('/api/units/{unit}/sub-units', function (Unit $unit) {
            return response()->json(
                $unit->subUnits()->orderBy('nama_sub_unit')->get(['id', 'nama_sub_unit'])
            );
        })->name('api.units.sub-units');
    });

    Route::prefix('operator')->name('operator.')->middleware('role:operator')->group(function () {
        Route::get('/dashboard', fn() => view('operator.dashboard'))->name('dashboard');
    });
});

// Halaman publik kendaraan berdasarkan token QR (diletakkan di paling bawah agar tidak bentrok dengan rute lain)
Route::get('/{token}', [PublicKendaraanController::class, 'show'])
     ->name('kendaraan.publik')
     ->where('token', '[A-Za-z0-9]{9}');