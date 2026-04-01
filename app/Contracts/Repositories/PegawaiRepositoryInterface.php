<?php

namespace App\Contracts\Repositories;

use App\Models\Pegawai;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PegawaiRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function allWithRelations(): Collection;

    public function units(): Collection;

    public function create(array $data): Pegawai;

    public function update(Pegawai $pegawai, array $data): Pegawai;

    public function delete(Pegawai $pegawai): void;
}
