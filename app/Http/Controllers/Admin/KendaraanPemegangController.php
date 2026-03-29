<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\KendaraanPemegang;
use App\Models\Pegawai;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Concerns\RoleRoutePrefix;

class KendaraanPemegangController extends Controller
{
    use RoleRoutePrefix;
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
                    'nama' => $pemegangLama->nama_pegawai ?? ($pemegangLama->pegawai->nama ?? 'Pegawai Internal'),
                    'nip'  => $pemegangLama->nip ?? ($pemegangLama->pegawai->nip ?? '-'),
                ],
            ]);
        }

        // Jika AJAX dan tidak ada pemegang aktif → kembalikan JSON untuk trigger redirect di JS
        if (!$pemegangLama && $isAjax && !$request->boolean('force_replace')) {
            // Proses simpan langsung, lalu return JSON sukses
            DB::transaction(function () use ($request, $kendaraan) {
                $isManual = ($request->source_system === 'Manual');
                $jabatanBaru = ''; 
                if ($isManual) {
                    $peg = Pegawai::with('unit')->find($request->pegawai_id);
                    $namaBaru = $peg->nama;
                    $nipBaru = $peg->nip;
                    $jabatanBaru = $peg->jabatan;
                    $opdBaru = $peg->unit?->nama_unit;
                } else {
                    $service = new \App\Services\PegawaiInternalService();
                    $internalPeg = $service->fetchPegawaiByNip($request->nip);
                    $namaBaru = $internalPeg ? $internalPeg['nama'] : 'Unknown (API)';
                    $nipBaru = $request->nip;
                    $jabatanBaru = $internalPeg ? $internalPeg['jabatan'] : '—';
                    $opdBaru = $internalPeg ? $internalPeg['opd'] : '';
                    
                    // Tambahkan keterangan pangkat jika ada di data API
                    if ($internalPeg && !empty($internalPeg['pangkat']) && $internalPeg['pangkat'] !== ' ()') {
                        $jabatanBaru .= " | " . $internalPeg['pangkat'];
                    }
                }

                KendaraanPemegang::create([
                    'kendaraan_id'    => $kendaraan->id,
                    'source_system'   => $request->source_system,
                    'pegawai_id'      => $isManual ? $request->pegawai_id : null,
                    'nip'             => $isManual ? null : $request->nip,
                    'nama_pegawai'    => $namaBaru,
                    'jabatan_pegawai' => $jabatanBaru,
                    'unit_pegawai'    => $opdBaru,
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
                    "Kendaraan: {$kendaraan->nama_kendaraan}, Pegawai: {$namaBaru}, NIP: {$nipBaru}, Source: {$request->source_system}, SK: {$request->nomor_sk}"
                );

                $deskripsiAktivitas = "Pegawai {$namaBaru} (NIP: {$nipBaru}) ditugaskan sebagai pemegang kendaraan. SK: {$request->nomor_sk}.";
                if (!$isManual && $opdBaru) {
                    $deskripsiAktivitas .= " (OPD: {$opdBaru})";
                }

                \App\Models\KendaraanAktivitas::create([
                    'kendaraan_id'      => $kendaraan->id,
                    'judul_aktivitas'   => 'Penugasan Pemegang Kendaraan',
                    'deskripsi'         => $deskripsiAktivitas,
                    'tanggal_aktivitas' => $request->tanggal_mulai,
                    'created_by'        => \Illuminate\Support\Facades\Auth::id(),
                ]);
            });

            return response()->json(['success' => true]);
        }

        // Submit akhir (force_replace = 1 atau non-AJAX): proses serah terima
        DB::transaction(function () use ($request, $kendaraan, $pemegangLama) {
            $isManual = ($request->source_system === 'Manual');
            $jabatanBaru = '';
            if ($isManual) {
                $peg = Pegawai::with('unit')->find($request->pegawai_id);
                $namaBaru = $peg->nama;
                $nipBaru = $peg->nip;
                $jabatanBaru = $peg->jabatan;
                $opdBaru = $peg->unit?->nama_unit;
            } else {
                $service = new \App\Services\PegawaiInternalService();
                $internalPeg = $service->fetchPegawaiByNip($request->nip);
                $namaBaru = $internalPeg ? $internalPeg['nama'] : 'Unknown (API)';
                $nipBaru = $request->nip;
                $jabatanBaru = $internalPeg ? $internalPeg['jabatan'] : '—';
                $opdBaru = $internalPeg ? $internalPeg['opd'] : '';

                if ($internalPeg && !empty($internalPeg['pangkat']) && $internalPeg['pangkat'] !== ' ()') {
                    $jabatanBaru .= " | " . $internalPeg['pangkat'];
                }
            }
            
            $today = now()->toDateString();
            $namaLama = $pemegangLama ? ($pemegangLama->nama_pegawai ?? ($pemegangLama->pegawai ? $pemegangLama->pegawai->nama : 'Pegawai Internal')) : '';
            $nipLama  = $pemegangLama ? ($pemegangLama->nip ?? ($pemegangLama->pegawai->nip ?? '-')) : '';

            if ($pemegangLama) {
                $pemegangLama->update([
                    'is_active'       => false,
                    'tanggal_selesai' => $today,
                ]);

                ActivityLogger::log(
                    'NONAKTIF PEMEGANG',
                    'Kendaraan',
                    $kendaraan->id,
                    "Kendaraan: {$kendaraan->nama_kendaraan}, Pegawai Dilepas: {$namaLama}, NIP: {$nipLama}"
                );
            }

            KendaraanPemegang::create([
                'kendaraan_id'    => $kendaraan->id,
                'source_system'   => $request->source_system,
                'pegawai_id'      => $isManual ? $request->pegawai_id : null,
                'nip'             => $isManual ? null : $request->nip,
                'nama_pegawai'    => $namaBaru,
                'jabatan_pegawai' => $jabatanBaru,
                'unit_pegawai'    => $opdBaru,
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
                "Kendaraan: {$kendaraan->nama_kendaraan}, Pegawai: {$namaBaru}, NIP: {$nipBaru}, Source: {$request->source_system}, SK: {$request->nomor_sk}"
            );

            $deskripsiAktivitas = "";
            $judulAktivitas = "";

            if ($pemegangLama) {
                ActivityLogger::log(
                    'SERAH TERIMA PEMEGANG',
                    'Kendaraan',
                    $kendaraan->id,
                    "Kendaraan: {$kendaraan->nama_kendaraan}, Dari: {$namaLama} → {$namaBaru}, SK: {$request->nomor_sk}"
                );
                $judulAktivitas = 'Serah Terima Pemegang Kendaraan';
                $deskripsiAktivitas = "Serah terima kendaraan dari {$namaLama} kepada {$namaBaru}. SK: {$request->nomor_sk}.";
            } else {
                $judulAktivitas = 'Penugasan Pemegang Kendaraan';
                $deskripsiAktivitas = "Pegawai {$namaBaru} (NIP: {$nipBaru}) ditugaskan sebagai pemegang kendaraan. SK: {$request->nomor_sk}.";
            }
            if (!$isManual && $opdBaru) {
                $deskripsiAktivitas .= " (OPD: {$opdBaru})";
            }

            \App\Models\KendaraanAktivitas::create([
                'kendaraan_id'      => $kendaraan->id,
                'judul_aktivitas'   => $judulAktivitas,
                'deskripsi'         => $deskripsiAktivitas,
                'tanggal_aktivitas' => $request->tanggal_mulai,
                'created_by'        => \Illuminate\Support\Facades\Auth::id(),
            ]);
        });

        return redirect()
            ->route($this->rp() . '.kendaraan.show', ['kendaraan' => $kendaraan->id, 'tab' => 'pemegang'])
            ->with('success', 'Pemegang kendaraan berhasil diperbarui.');
    }
}
