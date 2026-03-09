<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\KendaraanPemegang;
use App\Models\Pegawai;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KendaraanPemegangController extends Controller
{
    /**
     * Proses Ganti / Assign Pemegang Kendaraan.
     * Menjalankan algoritma serah terima:
     *  1. Cek pemegang aktif
     *  2. Jika ada dan belum dikonfirmasi → kembalikan JSON konfirmasi
     *  3. Jika sudah dikonfirmasi → nonaktifkan lama, buat baru
     */
    public function store(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'pegawai_id'    => 'required|exists:pegawais,id',
            'nomor_sk'      => 'required|string|max:100',
            'tanggal_sk'    => 'required|date',
            'tanggal_mulai' => 'required|date',
        ]);

        $pemegangLama = KendaraanPemegang::where('kendaraan_id', $kendaraan->id)
            ->where('is_active', true)
            ->with('pegawai')
            ->first();

        $isAjax = $request->ajax() || $request->wantsJson();

        // Jika ada pemegang aktif, belum dikonfirmasi, dan request via AJAX → kembalikan JSON untuk konfirmasi
        if ($pemegangLama && !$request->boolean('force_replace') && $isAjax) {
            return response()->json([
                'needs_confirm' => true,
                'pemegang_lama' => [
                    'nama' => $pemegangLama->pegawai->nama,
                    'nip'  => $pemegangLama->pegawai->nip,
                ],
            ]);
        }

        // Jika AJAX dan tidak ada pemegang aktif → kembalikan JSON untuk trigger redirect di JS
        if (!$pemegangLama && $isAjax && !$request->boolean('force_replace')) {
            // Proses simpan langsung, lalu return JSON sukses
            DB::transaction(function () use ($request, $kendaraan) {
                $pegawaiBaru = Pegawai::findOrFail($request->pegawai_id);

                KendaraanPemegang::create([
                    'kendaraan_id'    => $kendaraan->id,
                    'pegawai_id'      => $pegawaiBaru->id,
                    'nomor_sk'        => $request->nomor_sk,
                    'tanggal_sk'      => $request->tanggal_sk,
                    'tanggal_mulai'   => $request->tanggal_mulai,
                    'tanggal_selesai' => null,
                    'is_active'       => true,
                ]);

                ActivityLogger::log(
                    'TAMBAH PEMEGANG',
                    'Kendaraan',
                    $kendaraan->id,
                    "Kendaraan: {$kendaraan->nama_kendaraan}, Pegawai: {$pegawaiBaru->nama}, NIP: {$pegawaiBaru->nip}, SK: {$request->nomor_sk}"
                );

                // Catat ke Riwayat Aktivitas Kendaraan
                \App\Models\KendaraanAktivitas::create([
                    'kendaraan_id'      => $kendaraan->id,
                    'judul_aktivitas'   => 'Penugasan Pemegang Kendaraan',
                    'deskripsi'         => "Pegawai {$pegawaiBaru->nama} (NIP: {$pegawaiBaru->nip}) ditugaskan sebagai pemegang kendaraan. SK: {$request->nomor_sk}.",
                    'tanggal_aktivitas' => $request->tanggal_mulai,
                    'created_by'        => \Illuminate\Support\Facades\Auth::id(),
                ]);
            });

            return response()->json(['success' => true]);
        }

        // Submit akhir (force_replace = 1 atau non-AJAX): proses serah terima
        DB::transaction(function () use ($request, $kendaraan, $pemegangLama) {
            $pegawaiBaru = Pegawai::findOrFail($request->pegawai_id);
            $today = now()->toDateString();

            if ($pemegangLama) {
                $pemegangLama->update([
                    'is_active'       => false,
                    'tanggal_selesai' => $today,
                ]);

                ActivityLogger::log(
                    'NONAKTIF PEMEGANG',
                    'Kendaraan',
                    $kendaraan->id,
                    "Kendaraan: {$kendaraan->nama_kendaraan}, Pegawai Dilepas: {$pemegangLama->pegawai->nama}, NIP: {$pemegangLama->pegawai->nip}"
                );
            }

            KendaraanPemegang::create([
                'kendaraan_id'    => $kendaraan->id,
                'pegawai_id'      => $pegawaiBaru->id,
                'nomor_sk'        => $request->nomor_sk,
                'tanggal_sk'      => $request->tanggal_sk,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => null,
                'is_active'       => true,
            ]);

            ActivityLogger::log(
                'TAMBAH PEMEGANG',
                'Kendaraan',
                $kendaraan->id,
                "Kendaraan: {$kendaraan->nama_kendaraan}, Pegawai: {$pegawaiBaru->nama}, NIP: {$pegawaiBaru->nip}, SK: {$request->nomor_sk}"
            );

            if ($pemegangLama) {
                ActivityLogger::log(
                    'SERAH TERIMA PEMEGANG',
                    'Kendaraan',
                    $kendaraan->id,
                    "Kendaraan: {$kendaraan->nama_kendaraan}, Dari: {$pemegangLama->pegawai->nama} → {$pegawaiBaru->nama}, SK: {$request->nomor_sk}"
                );

                // Catat ke Riwayat Aktivitas Kendaraan
                \App\Models\KendaraanAktivitas::create([
                    'kendaraan_id'      => $kendaraan->id,
                    'judul_aktivitas'   => 'Serah Terima Pemegang Kendaraan',
                    'deskripsi'         => "Serah terima kendaraan dari {$pemegangLama->pegawai->nama} kepada {$pegawaiBaru->nama}. SK: {$request->nomor_sk}.",
                    'tanggal_aktivitas' => $request->tanggal_mulai,
                    'created_by'        => \Illuminate\Support\Facades\Auth::id(),
                ]);
            } else {
                // Catat ke Riwayat Aktivitas Kendaraan (Jika baru pertama kali assign)
                \App\Models\KendaraanAktivitas::create([
                    'kendaraan_id'      => $kendaraan->id,
                    'judul_aktivitas'   => 'Penugasan Pemegang Kendaraan',
                    'deskripsi'         => "Pegawai {$pegawaiBaru->nama} (NIP: {$pegawaiBaru->nip}) ditugaskan sebagai pemegang kendaraan. SK: {$request->nomor_sk}.",
                    'tanggal_aktivitas' => $request->tanggal_mulai,
                    'created_by'        => \Illuminate\Support\Facades\Auth::id(),
                ]);
            }
        });

        return redirect()
            ->route('admin.kendaraan.show', ['kendaraan' => $kendaraan->id, 'tab' => 'pemegang'])
            ->with('success', 'Pemegang kendaraan berhasil diperbarui.');
    }
}
