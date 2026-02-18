<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StepLoginController;

Route::redirect('/', '/login');
Route::get('/login', [StepLoginController::class, 'showNipForm'])->name('login');
Route::post('/login-nip', [StepLoginController::class, 'checkNip'])->name('login.nip');

Route::get('/login-password', [StepLoginController::class, 'showPasswordForm'])->name('login.password');

Route::post('/login-password', [StepLoginController::class, 'submitPassword'])->name('login.password.submit');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn()=>view('dashboard'));
});