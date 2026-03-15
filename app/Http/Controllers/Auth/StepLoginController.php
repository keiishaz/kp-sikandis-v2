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
    public function showNipForm()
    {
        return view('auth.login-nip');
    }

    public function checkNip(Request $r)
    {
        $r->validate(['nip'=>'required']);

        $user = User::where('nip', $r->nip)->first();

        if (!$user) {
            \App\Services\LoginLogger::log('LOGIN FAIL', "NIP tidak ditemukan: {$r->nip}");
            return back()->withErrors(['nip'=>'NIP tidak ditemukan']);
        }

        if ($user->locked_until && now()->lt($user->locked_until)) {
            \App\Services\LoginLogger::log('LOGIN BLOCKED', $user->nip);
            $sec = now()->diffInSeconds($user->locked_until);
            return back()->withErrors(['nip'=>"Akun dikunci {$sec} detik"]);
        }

        session(['login_nip' => $user->nip]);

        return redirect('/login-password');
    }

    public function showPasswordForm()
    {
        abort_unless(session('login_nip'), 403);
        return view('auth.login-password');
    }

    public function submitPassword(\Illuminate\Http\Request $r)
    {
        $r->merge(['nip' => session('login_nip')]);

        return app(\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class)->store(
            app(\Laravel\Fortify\Http\Requests\LoginRequest::class)
        );
    }

    }
