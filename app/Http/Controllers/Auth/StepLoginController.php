<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class StepLoginController extends Controller
{
    public function showNikForm()
    {
        return view('auth.login-nik');
    }

    public function checkNik(Request $r)
    {
        $r->validate([
            'nik' => 'required|string|size:16',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus berjumlah 16 digit angka.'
        ]);

        $user = User::where('nik', $r->nik)->first();

        if (!$user) {
            \App\Services\LoginLogger::log('LOGIN FAIL', "NIK tidak ditemukan: {$r->nik}");
            return back()->withErrors(['nik'=>'NIK tidak ditemukan']);
        }

        if ($user->locked_until && now()->lt($user->locked_until)) {
            \App\Services\LoginLogger::log('LOGIN BLOCKED', $user->nik);
            $sec = now()->diffInSeconds($user->locked_until);
            return back()->withErrors(['nik'=>"Akun dikunci {$sec} detik"]);
        }

        session(['login_nik' => $user->nik]);

        return redirect('/login-password');
    }

    public function showPasswordForm()
    {
        if (!session('login_nik')) {
            return redirect()->route('login');
        }

        return view('auth.login-password');
    }

    public function submitPassword(\Illuminate\Http\Request $r)
    {
        if (!session('login_nik')) {
            return redirect()->route('login');
        }

        $r->merge(['nik' => session('login_nik')]);

        return app(\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class)->store(
            app(\Laravel\Fortify\Http\Requests\LoginRequest::class)
        );
    }
}
