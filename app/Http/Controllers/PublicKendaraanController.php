<?php

namespace App\Http\Controllers;

use App\Models\QrKendaraan;
use Illuminate\Http\Request;

class PublicKendaraanController extends Controller
{
    /**
     * Halaman publik kendaraan berdasarkan token QR.
     * URL: /scan/{kode}
     */
    public function show(string $kode)
    {
        $qr = QrKendaraan::with([
            'kendaraan.kategori',
            'kendaraan.pemegangAktif.pegawai.unit',
            'kendaraan.pemegangAktif.pegawai.subUnit',
        ])->where('token', $kode)->first();

        // Tampilkan halaman not-found jika token tidak ada di database
        if (! $qr || ! $qr->kendaraan) {
            return response(view('umum.not-found'), 404);
        }

        // Increment counter scan secara atomic
        $qr->incrementScan();

        $kendaraan = $qr->kendaraan;

        return view('umum.public', compact('kendaraan', 'qr'));
    }
}
