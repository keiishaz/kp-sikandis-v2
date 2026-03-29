<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface DashboardRepositoryInterface
{
    public function kendaraanStats(): array;

    public function pajakStats(): array;

    public function pemegangStats(int $totalKendaraan): array;

    public function qrStats(): array;

    public function qrChartData(): array;

    public function masterDataStats(): array;

    public function distribusiKategori(): Collection;

    public function kendaraanTerbaru(int $limit = 6): Collection;

    public function daftarPajakSegera(int $limit = 5): Collection;
}
