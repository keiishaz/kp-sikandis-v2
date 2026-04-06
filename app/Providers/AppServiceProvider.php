<?php

namespace App\Providers;

use App\Contracts\Repositories\DashboardRepositoryInterface;
use App\Contracts\Repositories\KategoriRepositoryInterface;
use App\Contracts\Repositories\KendaraanRepositoryInterface;
use App\Contracts\Repositories\OperatorRepositoryInterface;
use App\Contracts\Repositories\PegawaiRepositoryInterface;
use App\Repositories\DashboardRepository;
use App\Repositories\KategoriRepository;
use App\Repositories\KendaraanRepository;
use App\Repositories\OperatorRepository;
use App\Repositories\PegawaiRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository Bindings — Interface → Concrete Implementation
        $this->app->bind(KendaraanRepositoryInterface::class, KendaraanRepository::class);
        $this->app->bind(KategoriRepositoryInterface::class, KategoriRepository::class);
        $this->app->bind(PegawaiRepositoryInterface::class, PegawaiRepository::class);
        $this->app->bind(OperatorRepositoryInterface::class, OperatorRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.sikandis');

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Gate::define('is-admin', fn($user) => $user->role?->nama_role === 'admin');
        Gate::define('is-operator', fn($user) => $user->role?->nama_role === 'operator');
        Gate::define('manage-master', fn($user) => $user->role?->nama_role === 'admin');
        Gate::define('view-log', fn($user) => $user->role?->nama_role === 'admin');
        Gate::define('delete-kendaraan', fn($user) => in_array($user->role?->nama_role, ['admin', 'operator']));
    }
}
