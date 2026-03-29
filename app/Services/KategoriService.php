<?php

namespace App\Services;

use App\Contracts\Repositories\KategoriRepositoryInterface;
use App\Models\Kategori;

class KategoriService
{
    public function __construct(
        private readonly KategoriRepositoryInterface $kategoriRepo,
    ) {}

    public function paginatedList(string $search = ''): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->kategoriRepo->paginate($search);
    }

    public function create(array $data): Kategori
    {
        $kategori = $this->kategoriRepo->create($data);

        ActivityLogger::log('TAMBAH KATEGORI KENDARAAN', 'Kategori', $kategori->id, "Nama Kategori: {$kategori->nama_kategori}");

        return $kategori;
    }

    public function update(Kategori $kategori, array $data): Kategori
    {
        $oldName  = $kategori->nama_kategori;
        $kategori = $this->kategoriRepo->update($kategori, $data);

        ActivityLogger::log('EDIT KATEGORI KENDARAAN', 'Kategori', $kategori->id, "Dari: {$oldName} → {$kategori->nama_kategori}");

        return $kategori;
    }

    public function delete(Kategori $kategori): void
    {
        $nama = $kategori->nama_kategori;
        $id   = $kategori->id;

        $this->kategoriRepo->delete($kategori);

        ActivityLogger::log('HAPUS KATEGORI KENDARAAN', 'Kategori', $id, "Nama Kategori: {$nama}");
    }
}
