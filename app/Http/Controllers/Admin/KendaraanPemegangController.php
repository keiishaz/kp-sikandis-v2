<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\RoleRoutePrefix;
use App\Models\Kendaraan;
use App\Services\KendaraanPemegangService;
use Illuminate\Http\Request;

class KendaraanPemegangController extends Controller
{
    use RoleRoutePrefix;

    public function __construct(private readonly KendaraanPemegangService $pemegangService) {}

    /**
     * Assign atau ganti pemegang kendaraan.
     * Jika pemegang aktif ditemukan dan belum dikonfirmasi (AJAX non-force) → return JSON konfirmasi.
     * Jika tidak ada atau sudah dikonfirmasi (force_replace) → proses serah terima.
     */
    public function store(Request $request, Kendaraan $kendaraan)
    {
        $this->validateRequest($request);

        $pemegangLama = $this->pemegangService->getActivePemegang($kendaraan);
        $isAjax       = $request->ajax() || $request->wantsJson();

        // Pemegang aktif ada & belum dikonfirmasi penggantiannya via AJAX
        if ($pemegangLama && ! $request->boolean('force_replace') && $isAjax) {
            return response()->json([
                'needs_confirm' => true,
                'pemegang_lama' => [
                    'nama' => $pemegangLama->nama_pegawai ?? ($pemegangLama->pegawai?->nama ?? 'Pegawai Internal'),
                    'nip'  => $pemegangLama->nip ?? ($pemegangLama->pegawai?->nip ?? '-'),
                ],
            ]);
        }

        // Tidak ada pemegang lama, AJAX tanpa force_replace → proses langsung, return JSON sukses
        if (! $pemegangLama && $isAjax && ! $request->boolean('force_replace')) {
            $this->pemegangService->assign($request->all(), $kendaraan, null);

            return response()->json(['success' => true]);
        }

        // Form POST biasa atau AJAX dengan force_replace → proses serah terima
        $this->pemegangService->assign($request->all(), $kendaraan, $pemegangLama);

        return redirect()
            ->route($this->rp() . '.kendaraan.show', ['kendaraan' => $kendaraan->id, 'tab' => 'pemegang'])
            ->with('success', 'Pemegang kendaraan berhasil diperbarui.');
    }

    private function validateRequest(Request $request): void
    {
        $request->validate([
            'source_system' => 'required|in:API,Manual',
            'nomor_sk'      => 'required|string|max:100',
            'tanggal_sk'    => 'required|date',
            'tanggal_mulai' => 'required|date',
        ]);

        if ($request->source_system === 'Manual') {
            $request->validate(['pegawai_id' => 'required|exists:pegawais,id']);
        } else {
            $request->validate(['nip' => 'required|string|max:18']);
        }
    }
}
