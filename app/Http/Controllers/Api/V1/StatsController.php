<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\QrKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Get scan statistics.
     * 
     * GET /api/v1/stats
     * GET /api/v1/stats?plat=BD1234AB
     */
    public function index(Request $request)
    {
        $plat = $request->query('plat');

        try {
            if ($plat) {
                // Skenario B: Cari spesifik berdasarkan nomor polisi
                $kendaraan = Kendaraan::where('no_polisi', $plat)
                    ->with('qr')
                    ->first();

                if (! $kendaraan) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Kendaraan dengan nomor polisi tersebut tidak ditemukan.'
                    ], 404);
                }

                return response()->json([
                    'status' => 'success',
                    'data'   => [
                        'no_polisi'   => $kendaraan->no_polisi,
                        'nama'        => $kendaraan->nama_kendaraan,
                        'scan_count'  => (int) ($kendaraan->qr->scan_count ?? 0),
                        'last_update' => $kendaraan->updated_at->format('Y-m-d H:i:s'),
                    ]
                ]);
            }

            // Skenario A: Total scan seluruh kendaraan
            $totalScan = (int) QrKendaraan::sum('scan_count');

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'total_scans' => $totalScan,
                    'last_update' => now()->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
