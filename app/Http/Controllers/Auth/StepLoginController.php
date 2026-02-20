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

    public function submitPassword(Request $r)
    {
        abort_unless(session('login_nip'), 403);

        $nip = session('login_nip');
        $user = User::where('nip', $nip)->first();

        // 1. Cek lock
        if ($user->locked_until && now()->lt($user->locked_until)) {
            // Log block already handled in Step 1 or previous attempts, but good to log if they force submit
             \App\Services\LoginLogger::log('LOGIN BLOCKED', $user->nip . ' (Attempt)');
            $sec = now()->diffInSeconds($user->locked_until);
            return back()->withErrors([
                'password' => "Tunggu {$sec} detik sebelum mencoba lagi"
            ])->with('locked_until', $user->locked_until);
        }

        // 2. Cek password manual
        if (Hash::check($r->password, $user->password)) {
            // SUCCESS
            $user->forceFill([
                'failed_login_attempts' => 0,
                'locked_until' => null,
                'last_login_at' => now(),
            ])->save();

            \App\Services\LoginLogger::log('LOGIN SUCCESS', $user->nip);

            // Manual Login
            Auth::login($user);
            $r->session()->regenerate();
            session()->forget('login_nip');

            // Redirect based on role
            if ($user->role && $user->role->nama_role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role && $user->role->nama_role === 'operator') {
                return redirect()->intended(route('operator.dashboard'));
            }
            
            return redirect()->intended(route('dashboard'));
        }

        // FAILURE
        $user->increment('failed_login_attempts');
        
        if ($user->failed_login_attempts >= 3) {
            $user->forceFill(['locked_until' => now()->addMinute()])->save();
            \App\Services\LoginLogger::log('LOGIN LOCKED 1 MIN', $user->nip);
        } else {
             \App\Services\LoginLogger::log('LOGIN FAIL PASSWORD', $user->nip);
        }

        $remain = max(0, 3 - $user->failed_login_attempts);
        $sec = $user->locked_until ? now()->diffInSeconds($user->locked_until) : 0;

        return back()->withErrors([
            'password' => $user->locked_until && $sec > 0
                ? "Akun dikunci. Tunggu {$sec} detik."
                : "Password salah. Sisa {$remain} percobaan"
        ])->with('locked_until', $user->locked_until);
    }

    }
