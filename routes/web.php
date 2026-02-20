<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\StepLoginController;
use App\Http\Controllers\Admin\KelolaOperatorController;

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
        Route::resource('kelola-operator', KelolaOperatorController::class)
             ->only(['index', 'store', 'edit', 'update', 'destroy'])
             ->names('kelola-operator');
    });

    Route::prefix('operator')->name('operator.')->middleware('role:operator')->group(function () {
        Route::get('/dashboard', fn() => view('operator.dashboard'))->name('dashboard');
    });
});