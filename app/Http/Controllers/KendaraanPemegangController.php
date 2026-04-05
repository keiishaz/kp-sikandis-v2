<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Services\KendaraanPemegangService;
use App\Services\PegawaiInternalService;
use Illuminate\Http\Request;

class KendaraanPemegangController extends Controller
{
    public function __construct(
        private readonly KendaraanPemegangService $pemegangService,
        private readonly PegawaiInternalService   $pegawaiInternalService,
    ) {}

    /**
     * Assign atau ganti pemegang kendaraan.
     *
     * Flow:
     * 1. Validate input.
     * 2. Check if existing active holder needs confirmation (serah terima).
     * 3. For Admin: check cross-OPD and return warning if mismatch & not yet confirmed.
     * 4. Process assignment.
     */
    public function store(Request $request, Kendaraan $kendaraan)
    {
        if ($request->has('nip')) {
            $request->merge(['nip' => trim($request->nip)]);
        }

        $this->validateRequest($request);

        $pemegangLama = $this->pemegangService->getActivePemegang($kendaraan);
        $isAjax       = $request->ajax() || $request->wantsJson();

        // ── Step 1: Confirm serah terima if there's an existing holder ──
        if ($pemegangLama && ! $request->boolean('force_replace') && $isAjax) {
            return response()->json([
                'needs_confirm' => true,
                'pemegang_lama' => [
                    'nama' => $pemegangLama->nama_pegawai ?? ($pemegangLama->pegawai?->nama ?? 'Pegawai Internal'),
                    'nip'  => $pemegangLama->nip ?? ($pemegangLama->pegawai?->nip ?? '-'),
                ],
            ]);
        }

        // ── Step 2: For Admin – Cross-OPD warning check ──
        if ($request->source_system === 'API' && $kendaraan->unit?->api_mapping_key && $isAjax) {
            $user = auth()->user();

            if ($user->isAdmin() && ! $request->boolean('force_replace_opd')) {
                $internal = $this->pegawaiInternalService->fetchPegawaiByNip($request->nip);
                $opdFromApi = $internal['opd'] ?? '';

                if ($opdFromApi && strtolower(trim($opdFromApi)) !== strtolower(trim($kendaraan->unit->api_mapping_key))) {
                    return response()->json([
                        'needs_opd_confirm' => true,
                        'opd_pegawai'       => $opdFromApi,
                        'unit_kendaraan'    => $kendaraan->unit->nama_unit,
                    ]);
                }
            }
        }

        // ── Step 3: No existing holder + AJAX → direct process ──
        if (! $pemegangLama && $isAjax && ! $request->boolean('force_replace')) {
            $this->pemegangService->assign($request->all(), $kendaraan, null);

            return response()->json(['success' => true]);
        }

        // ── Step 4: Form POST or confirmed AJAX → process serah terima ──
        $this->pemegangService->assign($request->all(), $kendaraan, $pemegangLama);

        return redirect()
            ->route('kendaraan.show', ['kendaraan' => $kendaraan->id, 'tab' => 'pemegang'])
            ->with('success', 'Pemegang kendaraan berhasil diperbarui.');
    }

    private function validateRequest(Request $request): void
    {
        $request->validate([
            'source_system' => 'required|in:API,Manual',
            'nomor_sk'      => 'required|string|max:100|unique:kendaraan_pemegangs,nomor_sk',
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

