<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\RoleRoutePrefix;
use App\Http\Requests\Admin\StoreKategoriRequest;
use App\Http\Requests\Admin\UpdateKategoriRequest;
use App\Models\Kategori;
use App\Services\KategoriService;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    use RoleRoutePrefix;

    public function __construct(private readonly KategoriService $kategoriService) {}

    public function index(Request $request)
    {
        $kategoris = $this->kategoriService->paginatedList($request->input('q', ''));

        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(StoreKategoriRequest $request)
    {
        $kategori = $this->kategoriService->create($request->validated());

        return redirect()->route($this->rp() . '.kategori.index')
                         ->with('success', "Kategori \"{$kategori->nama_kategori}\" berhasil ditambahkan.");
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(UpdateKategoriRequest $request, Kategori $kategori)
    {
        $kategori = $this->kategoriService->update($kategori, $request->validated());

        return redirect()->route($this->rp() . '.kategori.index')
                         ->with('success', "Kategori \"{$kategori->nama_kategori}\" berhasil diperbarui.");
    }

    public function destroy(Kategori $kategori)
    {
        $nama = $kategori->nama_kategori;
        $this->kategoriService->delete($kategori);

        return redirect()->route($this->rp() . '.kategori.index')
                         ->with('success', "Kategori \"{$nama}\" berhasil dihapus.");
    }
}
