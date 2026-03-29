<?php

namespace App\Repositories;

use App\Contracts\Repositories\KategoriRepositoryInterface;
use App\Models\Kategori;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class KategoriRepository implements KategoriRepositoryInterface
{
    public function paginate(string $search = '', int $perPage = 15): LengthAwarePaginator
    {
        $query = Kategori::query();

        if ($search) {
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        return $query->orderBy('nama_kategori')->paginate($perPage)->withQueryString();
    }

    public function all(): Collection
    {
        return Kategori::orderBy('nama_kategori', 'asc')->get();
    }

    public function create(array $data): Kategori
    {
        return Kategori::create($data);
    }

    public function update(Kategori $kategori, array $data): Kategori
    {
        $kategori->update($data);

        return $kategori->fresh();
    }

    public function delete(Kategori $kategori): void
    {
        $kategori->delete();
    }
}
