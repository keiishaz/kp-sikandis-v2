<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\QrKendaraan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TokenApiController extends Controller
{
    /**
     * Get real vehicle data based on QR token (Mirror Scan results).
     * URL: api/v1/token/{token}/get_scan
     * Silent fetch: Does NOT update stats or increment counters.
     */
    public function get_scan(string $token)
    {
        try {
            $qr = QrKendaraan::with([
                'kendaraan.kategori',
                'kendaraan.unit',
                'kendaraan.pemegangAktif.pegawai.unit',
                'kendaraan.pemegangAktif.pegawai.subUnit',
            ])->where('token', $token)->first();

            // Strict Validation: Not found or vehicle inactive
            if (! $qr || ! $qr->kendaraan || $qr->kendaraan->status !== 'aktif') {
                return response()->json([
                    'status' => 'not_found'
                ], 404);
            }

            $kendaraan = $qr->kendaraan;
            $pemegang = $kendaraan->pemegangAktif;

            // Prepare Information Kendaraan (Visual Mirror)
            $informasiKendaraan = [
                'nomor_polisi'    => $kendaraan->no_polisi,
                'kategori'        => $kendaraan->kategori->nama_kategori ?? '-',
                'tahun_keluaran'  => $kendaraan->tahun ?? '-',
                'warna_kendaraan' => $kendaraan->warna ?? '-',
                'jenis_penggunaan'=> ucfirst(str_replace('_', ' ', $kendaraan->jenis_penggunaan)),
            ];

            // Conditional field: Lokasi Operasional
            if ($kendaraan->lokasi_operasional) {
                $informasiKendaraan['lokasi_operasional'] = $kendaraan->lokasi_operasional;
            }

            // Prepare Daftar Pemegang (Dynamic source: API/Manual)
            $daftarPemegang = null;
            if ($pemegang && ($pemegang->pegawai || $pemegang->nama_pegawai)) {
                $daftarPemegang = [
                    'nama_pemegang' => $pemegang->display_name,
                    'jabatan'       => $pemegang->display_jabatan,
                    'unit_kerja'    => $pemegang->display_unit,
                    'memegang_sejak'=> $pemegang->tanggal_mulai ? 
                                       Carbon::parse($pemegang->tanggal_mulai)->locale('id')->translatedFormat('d F Y') : '-',
                ];
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'informasi_kendaraan' => $informasiKendaraan,
                    'daftar_pemegang'     => $daftarPemegang
                ]
            ]);

        } catch (\Exception $e) {
            // Strict Security: No internal leak
            return response()->json([
                'status' => 'error'
            ], 500);
        }
    }
}
