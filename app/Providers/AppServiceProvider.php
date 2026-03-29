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
    }
}
