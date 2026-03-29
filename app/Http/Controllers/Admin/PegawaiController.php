<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\RoleRoutePrefix;
use App\Http\Requests\Admin\StorePegawaiRequest;
use App\Http\Requests\Admin\UpdatePegawaiRequest;
use App\Models\Pegawai;
use App\Services\PegawaiService;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    use RoleRoutePrefix;

    public function __construct(private readonly PegawaiService $pegawaiService) {}

    public function index(Request $request)
    {
        $pegawais = $this->pegawaiService->paginatedList($request->input('q', ''));
        $units    = $this->pegawaiService->units();

        return view('admin.pegawai.index', compact('pegawais', 'units'));
    }

    public function create()
    {
        $units = $this->pegawaiService->units();

        return view('admin.pegawai.create', compact('units'));
    }

    public function store(StorePegawaiRequest $request)
    {
        $pegawai = $this->pegawaiService->create($request->validated());

        return redirect()->route($this->rp() . '.pegawai.index')
                         ->with('success', "Pegawai \"{$pegawai->nama}\" berhasil ditambahkan.");
    }

    public function edit(Pegawai $pegawai)
    {
        $units    = $this->pegawaiService->units();
        $subUnits = $pegawai->unit
            ? $pegawai->unit->subUnits()->orderBy('nama_sub_unit')->get()
            : collect();

        return view('admin.pegawai.edit', compact('pegawai', 'units', 'subUnits'));
    }

    public function update(UpdatePegawaiRequest $request, Pegawai $pegawai)
    {
        $pegawai = $this->pegawaiService->update($pegawai, $request->validated());

        return redirect()->route($this->rp() . '.pegawai.index')
                         ->with('success', "Pegawai \"{$pegawai->nama}\" berhasil diperbarui.");
    }

    public function destroy(Pegawai $pegawai)
    {
        $nama = $pegawai->nama;
        $this->pegawaiService->delete($pegawai);

        return redirect()->route($this->rp() . '.pegawai.index')
                         ->with('success', "Pegawai \"{$nama}\" berhasil dihapus.");
    }
}
