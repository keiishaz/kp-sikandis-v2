<?php

namespace App\Contracts\Repositories;

use App\Models\Kendaraan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface KendaraanRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function forPrint(array $filters): Collection;

    public function countForPrint(array $filters): int;

    public function countByStatus(): array;

    public function findWithRelations(Kendaraan $kendaraan): Kendaraan;

    public function create(array $data): Kendaraan;

    public function update(Kendaraan $kendaraan, array $data): Kendaraan;

    public function toggleStatus(Kendaraan $kendaraan): Kendaraan;
}
