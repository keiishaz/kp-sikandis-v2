<?php

namespace App\Services;

use App\Contracts\Repositories\PegawaiRepositoryInterface;
use App\Models\Pegawai;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PegawaiService
{
    public function __construct(
        private readonly PegawaiRepositoryInterface $pegawaiRepo,
    ) {}

    public function paginatedList(string $search = ''): LengthAwarePaginator
    {
        return $this->pegawaiRepo->paginate($search);
    }

    public function units(): Collection
    {
        return $this->pegawaiRepo->units();
    }

    public function create(array $data): Pegawai
    {
        $pegawai = $this->pegawaiRepo->create($data);

        ActivityLogger::log('TAMBAH PEGAWAI', 'Pegawai', $pegawai->id, "Nama: {$pegawai->nama} | NIK: {$pegawai->nik}");

        return $pegawai;
    }

    public function update(Pegawai $pegawai, array $data): Pegawai
    {
        $old     = $pegawai->nama;
        $pegawai = $this->pegawaiRepo->update($pegawai, $data);

        ActivityLogger::log('EDIT PEGAWAI', 'Pegawai', $pegawai->id, "Dari: {$old} → {$pegawai->nama} | NIK: {$pegawai->nik}");

        return $pegawai;
    }

    public function delete(Pegawai $pegawai): void
    {
        $nama = $pegawai->nama;
        $id   = $pegawai->id;

        $this->pegawaiRepo->delete($pegawai);

        ActivityLogger::log('HAPUS PEGAWAI', 'Pegawai', $id, "Nama: {$nama}");
    }
}
