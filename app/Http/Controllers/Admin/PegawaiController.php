<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePegawaiRequest;
use App\Http\Requests\Admin\UpdatePegawaiRequest;
use App\Models\Pegawai;
use App\Models\Unit;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with(['unit', 'subUnit']);

        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('nama', 'like', "%{$q}%")
                   ->orWhere('nip', 'like', "%{$q}%");
            });
        }

        $pegawais = $query->orderBy('nama')->paginate(15)->withQueryString();
        $units    = Unit::orderBy('nama_unit')->get();

        return view('admin.pegawai.index', compact('pegawais', 'units'));
    }

    public function create()
    {
        $units = Unit::orderBy('nama_unit')->get();

        return view('admin.pegawai.create', compact('units'));
    }

    public function store(StorePegawaiRequest $request)
    {
        $pegawai = Pegawai::create($request->validated());

        ActivityLogger::log(
            'TAMBAH PEGAWAI',
            'Pegawai',
            $pegawai->id,
            "Nama: {$pegawai->nama} | NIP: {$pegawai->nip}"
        );

        return redirect()->route('admin.pegawai.index')
                         ->with('success', "Pegawai \"{$pegawai->nama}\" berhasil ditambahkan.");
    }

    public function edit(Pegawai $pegawai)
    {
        $units    = Unit::orderBy('nama_unit')->get();
        $subUnits = $pegawai->unit
            ? $pegawai->unit->subUnits()->orderBy('nama_sub_unit')->get()
            : collect();

        return view('admin.pegawai.edit', compact('pegawai', 'units', 'subUnits'));
    }

    public function update(UpdatePegawaiRequest $request, Pegawai $pegawai)
    {
        $old = $pegawai->nama;
        $pegawai->update($request->validated());

        ActivityLogger::log(
            'EDIT PEGAWAI',
            'Pegawai',
            $pegawai->id,
            "Dari: {$old} → {$pegawai->nama} | NIP: {$pegawai->nip}"
        );

        return redirect()->route('admin.pegawai.index')
                         ->with('success', "Pegawai \"{$pegawai->nama}\" berhasil diperbarui.");
    }

    public function destroy(Pegawai $pegawai)
    {
        $nama = $pegawai->nama;
        $id   = $pegawai->id;
        $pegawai->delete();

        ActivityLogger::log('HAPUS PEGAWAI', 'Pegawai', $id, "Nama: {$nama}");

        return redirect()->route('admin.pegawai.index')
                         ->with('success', "Pegawai \"{$nama}\" berhasil dihapus.");
    }
}
