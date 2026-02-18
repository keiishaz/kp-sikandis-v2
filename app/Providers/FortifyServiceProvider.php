<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::redirectUserForTwoFactorAuthenticationUsing(
            RedirectIfTwoFactorAuthenticatable::class
        );

        // Fortify::loginView(function () {
        //     return view('auth.login');
        // });

        Fortify::authenticateUsing(function (Request $request) {

        $nip = $request->nip ?? session('login_nip'); // ambil dari session jika $request->nip kosong

        $user = User::where('nip', $nip)->first();

        if (!$user) {
            Log::warning('LOGIN FAIL — NIP tidak ditemukan: '.$nip);
            return null;
        }

        if ($user->locked_until && now()->lt($user->locked_until)) {
            Log::warning('LOGIN BLOCKED — '.$user->nip);
            return null;
        }

        if (Hash::check($request->password, $user->password)) {

            $user->failed_login_attempts = 0;
            $user->locked_until = null;
            $user->last_login_at = now();
            $user->save();

            Log::info('LOGIN SUCCESS — '.$user->nip);

            return $user;
        }

        // Password salah → tambah counter
        $user->failed_login_attempts += 1;

        if ($user->failed_login_attempts >= 3) {
            $user->locked_until = now()->addMinute();
            Log::warning('LOGIN LOCKED 1 MIN — '.$user->nip);
        }

        $user->save();

        Log::warning('LOGIN FAIL PASSWORD — '.$user->nip);

        return null;
    });

    }
}
