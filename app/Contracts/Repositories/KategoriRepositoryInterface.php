<?php

namespace App\Contracts\Repositories;

use App\Models\Kategori;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface KategoriRepositoryInterface
{
    public function paginate(string $search = '', int $perPage = 15): LengthAwarePaginator;

    public function all(): Collection;

    public function create(array $data): Kategori;

    public function update(Kategori $kategori, array $data): Kategori;

    public function delete(Kategori $kategori): void;
}
