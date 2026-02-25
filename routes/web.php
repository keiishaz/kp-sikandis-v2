<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\StepLoginController;
use App\Http\Controllers\Admin\KelolaOperatorController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\SubUnitController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\KategoriController;
use App\Models\Unit;

Route::redirect('/', '/login');
Route::middleware('guest')->group(function () {
    Route::get('/login', [StepLoginController::class, 'showNipForm'])->name('login');
    Route::post('/login-nip', [StepLoginController::class, 'checkNip'])->name('login.nip');
    Route::get('/login-password', [StepLoginController::class, 'showPasswordForm'])->name('login.password');
    Route::post('/login-password', [StepLoginController::class, 'submitPassword'])->name('login.password.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        // Modul Logs
        Route::get('/log/aktivitas', [App\Http\Controllers\Admin\LogViewerController::class, 'aktivitas'])->name('log.aktivitas');
        Route::get('/log/login', [App\Http\Controllers\Admin\LogViewerController::class, 'login'])->name('log.login');

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