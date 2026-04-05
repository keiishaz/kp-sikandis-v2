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
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
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
            $nik = $request->nik ?? session('login_nik');
            
            // Simpan metadata device/IP dari Request untuk pencatatan detail login sukses
            $ip = $request->ip();
            $userAgent = $request->userAgent();

            $user = User::where('nik', $nik)->first();

            if (!$user) {
                \App\Services\LoginLogger::log('LOGIN FAIL', "NIK tidak ditemukan: {$nik}", [
                    'ip' => $ip,
                    'user_agent' => $userAgent
                ]);
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'password' => "NIK tidak ditemukan"
                ]);
            }

            // Check if user is blocked
            if ($user->locked_until && now()->lt($user->locked_until)) {
                $sec = now()->diffInSeconds($user->locked_until);
                \App\Services\LoginLogger::log('LOGIN BLOCKED', $user->nik . " (Sisa {$sec} detik)", [
                    'ip' => $ip,
                    'user_agent' => $userAgent
                ]);
                session()->flash('locked_until', $user->locked_until);
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'password' => "Akun dikunci. Tunggu {$sec} detik sebelum mencoba lagi."
                ]);
            }

            if (Hash::check($request->password, $user->password)) {
                $user->forceFill([
                    'failed_login_attempts' => 0,
                    'locked_until' => null,
                    'last_login_at' => now(),
                    'last_login_ip' => $ip,
                    'last_login_user_agent' => $userAgent,
                ])->save();

                \App\Services\LoginLogger::log('LOGIN SUCCESS', $user->nik, [
                    'ip' => $ip,
                    'user_agent' => $userAgent
                ]);
                session()->forget('login_nik');

                return $user;
            }

            // Password wrong
            $user->increment('failed_login_attempts');
            
            if ($user->failed_login_attempts >= 5) {
                $user->forceFill(['locked_until' => now()->addMinutes(15)])->save();
                \App\Services\LoginLogger::log('LOGIN LOCKED 15 MIN', $user->nik, [
                    'ip' => $ip,
                    'user_agent' => $userAgent
                ]);
            } else {
                \App\Services\LoginLogger::log('LOGIN FAIL PASSWORD', $user->nik, [
                    'ip' => $ip,
                    'user_agent' => $userAgent
                ]);
            }

            $remain = max(0, 5 - $user->failed_login_attempts);
            $sec = $user->locked_until ? now()->diffInSeconds($user->locked_until) : 0;
            
            if ($user->locked_until) {
                session()->flash('locked_until', $user->locked_until);
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'password' => "Akun dikunci. Tunggu {$sec} detik sebelum mencoba lagi."
                ]);
            }

            throw \Illuminate\Validation\ValidationException::withMessages([
                'password' => "Password salah. Sisa {$remain} percobaan."
            ]);
        });

    }
}
