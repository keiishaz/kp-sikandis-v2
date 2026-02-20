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
            $nip = $request->nip ?? session('login_nip');
            
            // Log attempt context if needed or just NIP
            // $ip = $request->ip();

            $user = User::where('nip', $nip)->first();

            if (!$user) {
                \App\Services\LoginLogger::log('LOGIN FAIL', "NIP tidak ditemukan: {$nip}");
                return null;
            }

            // Check if user is blocked
            if ($user->locked_until && now()->lt($user->locked_until)) {
                \App\Services\LoginLogger::log('LOGIN BLOCKED', $user->nip);
                return null;
            }

            if (Hash::check($request->password, $user->password)) {
                $user->forceFill([
                    'failed_login_attempts' => 0,
                    'locked_until' => null,
                    'last_login_at' => now(),
                ])->save();

                \App\Services\LoginLogger::log('LOGIN SUCCESS', $user->nip);

                return $user;
            }

            // Password wrong
            $user->increment('failed_login_attempts');
            
            if ($user->failed_login_attempts >= 3) {
                $user->forceFill(['locked_until' => now()->addMinute()])->save();
                \App\Services\LoginLogger::log('LOGIN LOCKED 1 MIN', $user->nip);
            } else {
                \App\Services\LoginLogger::log('LOGIN FAIL PASSWORD', $user->nip);
            }

            return null;
        });

    }
}
