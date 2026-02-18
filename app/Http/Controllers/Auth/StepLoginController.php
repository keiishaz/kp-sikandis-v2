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

        $user = User::where('nip',$r->nip)->first();

        if(!$user){
            return back()->withErrors(['nip'=>'NIP tidak ditemukan']);
        }

        if ($user->locked_until && now()->lt($user->locked_until)) {
            $sec = now()->diffInSeconds($user->locked_until);
            return back()->withErrors(['nip'=>"Akun dikunci {$sec} detik"]);
        }

        session(['login_nip'=>$user->nip]);

        return redirect('/login-password');
    }

    public function showPasswordForm()
    {
        abort_unless(session('login_nip'),403);
        return view('auth.login-password');
    }

    public function submitPassword(Request $r)
    {
        abort_unless(session('login_nip'), 403);

        $user = User::where('nip', session('login_nip'))->first();

        // cek lock percobaan
        if ($user->locked_until && now()->lt($user->locked_until)) {
            $sec = now()->diffInSeconds($user->locked_until);
            return back()->withErrors([
                'password' => "Tunggu {$sec} detik sebelum mencoba lagi"
            ])->with('locked_until', $user->locked_until);
        }

        // cek password
        if (Hash::check($r->password, $user->password)) {
            // reset counter jika sukses
            $user->failed_login_attempts = 0;
            $user->locked_until = null;
            $user->last_login_at = now();
            $user->save();

            $r->session()->regenerate();
            session()->forget('login_nip');

            return redirect('/dashboard');
        }

        // salah password → tambah counter
        $user->failed_login_attempts += 1;

        if ($user->failed_login_attempts >= 3) {
            $user->locked_until = now()->addMinute();
        }

        $user->save();

        $remain = max(0, 3 - $user->failed_login_attempts);
        $sec = $user->locked_until ? now()->diffInSeconds($user->locked_until) : 0;

        return back()->withErrors([
            'password' => $user->locked_until && $sec > 0
                ? "Tunggu {$sec} detik sebelum mencoba lagi"
                : "Password salah. Sisa {$remain} percobaan"
        ])->with('locked_until', $user->locked_until);
    }

    }
