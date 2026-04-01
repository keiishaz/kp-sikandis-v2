<?php

namespace App\Repositories;

use App\Contracts\Repositories\KendaraanRepositoryInterface;
use App\Models\Kendaraan;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class KendaraanRepository implements KendaraanRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->buildBaseQuery($filters)
            ->with('kategori', 'pemegangAktif')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function forPrint(array $filters): Collection
    {
        return $this->buildBaseQuery($filters)
            ->with('kategori', 'pemegangAktif.pegawai.unit', 'pemegangAktif.pegawai.subUnit')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function countForPrint(array $filters): int
    {
        return $this->buildBaseQuery($filters)->count();
    }

    public function countByStatus(): array
    {
        return [
            'aktif'    => Kendaraan::where('status', 'aktif')->count(),
            'nonaktif' => Kendaraan::where('status', 'nonaktif')->count(),
        ];
    }

    public function findWithRelations(Kendaraan $kendaraan): Kendaraan
    {
        $kendaraan->load([
            'kategori',
            'qrKendaraan',
            'pemegangs'       => fn ($q) => $q->orderBy('tanggal_mulai', 'desc'),
            'pemegangs.pegawai.unit',
            'pemegangs.pegawai.subUnit',
            'aktivitas'       => fn ($q) => $q->orderBy('tanggal_aktivitas', 'desc')->orderBy('created_at', 'desc'),
            'aktivitas.creator',
        ]);

        return $kendaraan;
    }

    public function create(array $data): Kendaraan
    {
        return Kendaraan::create($data);
    }

    public function update(Kendaraan $kendaraan, array $data): Kendaraan
    {
        $kendaraan->update($data);

        return $kendaraan->fresh();
    }

    public function toggleStatus(Kendaraan $kendaraan): Kendaraan
    {
        $newStatus = $kendaraan->status === 'aktif' ? 'nonaktif' : 'aktif';
        $kendaraan->update(['status' => $newStatus]);

        return $kendaraan->fresh();
    }

    // ─── Private Helpers ────────────────────────────────────────────────────

    private function buildBaseQuery(array $filters): Builder
    {
        $status = $filters['status'] ?? 'aktif';
        $query  = Kendaraan::query()->where('status', $status);

        if (! empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('nama_kendaraan', 'like', "%{$search}%")
                  ->orWhere('no_polisi', 'like', "%{$search}%")
                  ->orWhereHas('kategori', fn ($qKat) => $qKat->where('nama_kategori', 'like', "%{$search}%"));
            });
        }

        if (! empty($filters['kategori_id'])) {
            $query->where('kategori_id', $filters['kategori_id']);
        }

        if (! empty($filters['jenis_penggunaan'])) {
            $query->where('jenis_penggunaan', $filters['jenis_penggunaan']);
        }

        if (! empty($filters['status_pajak'])) {
            $this->applyPajakFilter($query, $filters['status_pajak']);
        }

        // Filter by Unit/OPD (Manual or API)
        if (! empty($filters['unit_id'])) {
            $query->whereHas('pemegangAktif.pegawai', function ($q) use ($filters) {
                $q->where('unit_id', $filters['unit_id']);
            });
        }

        if (! empty($filters['opd_name'])) {
            $query->whereHas('pemegangAktif', function ($q) use ($filters) {
                $q->where('source_system', '!=', 'manual')
                  ->where('unit_pegawai', $filters['opd_name']);
            });
        }

        return $query;
    }

    private function applyPajakFilter(Builder $query, string $statusPajak): void
    {
        match ($statusPajak) {
            'telah_jatuh_tempo'   => $query->whereNotNull('pajak')->whereDate('pajak', '<', now()),
            'hampir_jatuh_tempo'  => $query->whereNotNull('pajak')
                                            ->whereDate('pajak', '>=', now())
                                            ->whereDate('pajak', '<=', now()->addMonths(6)),
            'aktif'               => $query->whereNotNull('pajak')->whereDate('pajak', '>', now()->addMonths(6)),
            default               => null,
        };
    }
}
