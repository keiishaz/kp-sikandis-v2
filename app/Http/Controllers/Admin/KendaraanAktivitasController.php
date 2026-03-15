<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\KendaraanAktivitas;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KendaraanAktivitasController extends Controller
{
    public function store(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'tanggal_aktivitas' => 'required|date',
            'judul_aktivitas'   => 'required|string|max:150',
            'deskripsi'         => 'nullable|string',
            'biaya_terpakai'    => 'nullable|numeric|min:0',
        ]);

        $aktivitas = KendaraanAktivitas::create([
            'kendaraan_id'      => $kendaraan->id,
            'judul_aktivitas'   => $request->judul_aktivitas,
            'deskripsi'         => $request->deskripsi,
            'tanggal_aktivitas' => $request->tanggal_aktivitas,
            'biaya_terpakai'    => $request->biaya_terpakai,
            'created_by'        => Auth::id(),
        ]);

        ActivityLogger::log(
            'TAMBAH AKTIVITAS KENDARAAN',
            'Kendaraan',
            $kendaraan->id,
            "Menambahkan aktivitas: {$request->judul_aktivitas}, Tanggal: {$request->tanggal_aktivitas}"
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Aktivitas berhasil ditambahkan']);
        }

        return redirect()->route('admin.kendaraan.show', ['kendaraan' => $kendaraan->id, 'tab' => 'aktivitas'])->with('success', 'Aktivitas berhasil ditambahkan');
    }

    public function update(Request $request, KendaraanAktivitas $aktivitas)
    {
        $request->validate([
            'tanggal_aktivitas' => 'required|date',
            'judul_aktivitas'   => 'required|string|max:150',
            'deskripsi'         => 'nullable|string',
            'biaya_terpakai'    => 'nullable|numeric|min:0',
        ]);

        $oldJudul = $aktivitas->judul_aktivitas;
        
        $aktivitas->update([
            'judul_aktivitas'   => $request->judul_aktivitas,
            'deskripsi'         => $request->deskripsi,
            'tanggal_aktivitas' => $request->tanggal_aktivitas,
            'biaya_terpakai'    => $request->biaya_terpakai,
        ]);

        ActivityLogger::log(
            'UPDATE AKTIVITAS KENDARAAN',
            'Kendaraan',
            $aktivitas->kendaraan_id,
            "Mengubah aktivitas dari '{$oldJudul}' menjadi '{$request->judul_aktivitas}'"
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Aktivitas berhasil diperbarui']);
        }

        return redirect()->route('admin.kendaraan.show', ['kendaraan' => $aktivitas->kendaraan_id, 'tab' => 'aktivitas'])->with('success', 'Aktivitas berhasil diperbarui');
    }

    public function destroy(KendaraanAktivitas $aktivitas)
    {
        $kendaraanId = $aktivitas->kendaraan_id;
        $judul = $aktivitas->judul_aktivitas;

        $aktivitas->delete();

        ActivityLogger::log(
            'HAPUS AKTIVITAS KENDARAAN',
            'Kendaraan',
            $kendaraanId,
            "Menghapus aktivitas: {$judul}"
        );

        return response()->json(['success' => true, 'message' => 'Aktivitas berhasil dihapus']);
    }
}
