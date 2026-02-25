<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKategoriRequest;
use App\Http\Requests\Admin\UpdateKategoriRequest;
use App\Models\Kategori;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::query();

        if ($q = $request->input('q')) {
            $query->where('nama_kategori', 'like', "%{$q}%");
        }

        $kategoris = $query->orderBy('nama_kategori')->paginate(15)->withQueryString();

        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(StoreKategoriRequest $request)
    {
        $kategori = Kategori::create($request->validated());

        ActivityLogger::log(
            'TAMBAH KATEGORI KENDARAAN',
            'Kategori',
            $kategori->id,
            "Nama Kategori: {$kategori->nama_kategori}"
        );

        return redirect()->route('admin.kategori.index')
                         ->with('success', "Kategori \"{$kategori->nama_kategori}\" berhasil ditambahkan.");
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(UpdateKategoriRequest $request, Kategori $kategori)
    {
        $oldName = $kategori->nama_kategori;
        $kategori->update($request->validated());

        ActivityLogger::log(
            'EDIT KATEGORI KENDARAAN',
            'Kategori',
            $kategori->id,
            "Dari: {$oldName} → {$kategori->nama_kategori}"
        );

        return redirect()->route('admin.kategori.index')
                         ->with('success', "Kategori \"{$kategori->nama_kategori}\" berhasil diperbarui.");
    }

    public function destroy(Kategori $kategori)
    {
        $nama = $kategori->nama_kategori;
        $id   = $kategori->id;
        $kategori->delete();

        ActivityLogger::log('HAPUS KATEGORI KENDARAAN', 'Kategori', $id, "Nama Kategori: {$nama}");

        return redirect()->route('admin.kategori.index')
                         ->with('success', "Kategori \"{$nama}\" berhasil dihapus.");
    }
}
