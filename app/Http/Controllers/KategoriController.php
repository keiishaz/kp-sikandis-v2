<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreKategoriRequest;
use App\Http\Requests\Admin\UpdateKategoriRequest;
use App\Models\Kategori;
use App\Services\KategoriService;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function __construct(private readonly KategoriService $kategoriService) {}

    public function index(Request $request)
    {
        $kategoris = $this->kategoriService->paginatedList($request->input('q', ''));

        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(StoreKategoriRequest $request)
    {
        $kategori = $this->kategoriService->create($request->validated());

        return redirect()->route('kategori.index')
                         ->with('success', "Kategori \"{$kategori->nama_kategori}\" berhasil ditambahkan.");
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(UpdateKategoriRequest $request, Kategori $kategori)
    {
        $kategori = $this->kategoriService->update($kategori, $request->validated());

        return redirect()->route('kategori.index')
                         ->with('success', "Kategori \"{$kategori->nama_kategori}\" berhasil diperbarui.");
    }

    public function destroy(Kategori $kategori)
    {
        $nama = $kategori->nama_kategori;
        $this->kategoriService->delete($kategori);

        return redirect()->route('kategori.index')
                         ->with('success', "Kategori \"{$nama}\" berhasil dihapus.");
    }
}
