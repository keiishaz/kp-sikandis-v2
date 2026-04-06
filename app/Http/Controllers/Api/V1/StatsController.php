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
        try {
            // Count all query parameters
            $queryCount = count($request->query());

            // SCENARIO 1: There are parameters in the URL
            if ($queryCount > 0) {
                // We ONLY allow 'no_polisi' as a search key
                if (! $request->has('no_polisi')) {
                    return response()->json([
                        'status' => 'failed'
                    ], 400);
                }

                $rawNoPolisi = $request->query('no_polisi');

                // STRICT: Reject if there is any space in the input
                if (str_contains($rawNoPolisi, ' ')) {
                    return response()->json([
                        'status' => 'failed'
                    ], 400);
                }

                $searchQuery = trim($rawNoPolisi);
                $searchQuery = preg_replace('/[^a-zA-Z0-9]/', '', $searchQuery);

                if (empty($searchQuery)) {
                    return response()->json([
                        'status' => 'failed'
                    ], 400);
                }

                $kendaraan = Kendaraan::whereRaw("REPLACE(no_polisi, ' ', '') = ?", [$searchQuery])
                    ->with('qrKendaraan')
                    ->first();

                // Explicit 404 if vehicle not found
                if (! $kendaraan) {
                    return response()->json([
                        'status' => 'not_found'
                    ], 404);
                }

                return response()->json([
                    'status' => 'success',
                    'data'   => [
                        'no_polisi'   => $kendaraan->no_polisi,
                        'nama'        => $kendaraan->nama_kendaraan,
                        'scan_count'  => (int) ($kendaraan->qrKendaraan->scan_count ?? 0),
                        'last_update' => $kendaraan->updated_at->format('Y-m-d H:i:s'),
                    ]
                ]);
            }

            // SCENARIO 2: Pure global total (no parameters at all)
            $totalScan  = (int) QrKendaraan::sum('scan_count');
            $lastUpdate = QrKendaraan::where('scan_count', '>', 0)->max('updated_at');

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'total_scans' => $totalScan,
                    'last_update' => $lastUpdate ? \Carbon\Carbon::parse($lastUpdate)->format('Y-m-d H:i:s') : null,
                ]
            ]);
        } catch (\Exception $e) {
            // STRICT SECURITY: Do not leak internal error messages
            return response()->json([
                'status' => 'error'
            ], 500);
        }
    }
}
