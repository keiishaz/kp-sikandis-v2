<?php

namespace App\Repositories;

use App\Contracts\Repositories\PegawaiRepositoryInterface;
use App\Models\Pegawai;
use App\Models\Unit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PegawaiRepository implements PegawaiRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Pegawai::with(['unit', 'subUnit']);
        $search = $filters['q'] ?? '';

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }

        if (!empty($filters['sub_unit_id'])) {
            $query->where('sub_unit_id', $filters['sub_unit_id']);
        }

        return $query->orderBy('nama')->paginate($perPage)->withQueryString();
    }

    public function allWithRelations(): Collection
    {
        return Pegawai::with(['unit', 'subUnit'])->orderBy('nama')->get();
    }

    public function units(): Collection
    {
        return Unit::orderBy('nama_unit')->get();
    }

    public function create(array $data): Pegawai
    {
        return Pegawai::create($data);
    }

    public function update(Pegawai $pegawai, array $data): Pegawai
    {
        $pegawai->update($data);

        return $pegawai->fresh();
    }

    public function delete(Pegawai $pegawai): void
    {
        $pegawai->delete();
    }
}
