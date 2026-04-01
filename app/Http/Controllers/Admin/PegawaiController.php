<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\StorePegawaiRequest;
use App\Http\Requests\Admin\UpdatePegawaiRequest;
use App\Models\Pegawai;
use App\Services\PegawaiService;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    

    public function __construct(private readonly PegawaiService $pegawaiService) {}

    public function index(Request $request)
    {
        $pegawais = $this->pegawaiService->paginatedList($request->all());
        $units    = $this->pegawaiService->units();
        
        $selectedUnitId = $request->input('unit_id');
        $subUnits = $selectedUnitId ? $this->pegawaiService->subUnits($selectedUnitId) : collect();

        return view('pegawai.index', compact('pegawais', 'units', 'subUnits'));
    }

    public function create()
    {
        $units = $this->pegawaiService->units();

        return view('pegawai.create', compact('units'));
    }

    public function store(StorePegawaiRequest $request)
    {
        $pegawai = $this->pegawaiService->create($request->validated());

        return redirect()->route('pegawai.index')
                         ->with('success', "Pegawai \"{$pegawai->nama}\" berhasil ditambahkan.");
    }

    public function edit(Pegawai $pegawai)
    {
        $units    = $this->pegawaiService->units();
        $subUnits = $pegawai->unit
            ? $pegawai->unit->subUnits()->orderBy('nama_sub_unit')->get()
            : collect();

        return view('pegawai.edit', compact('pegawai', 'units', 'subUnits'));
    }

    public function update(UpdatePegawaiRequest $request, Pegawai $pegawai)
    {
        $pegawai = $this->pegawaiService->update($pegawai, $request->validated());

        return redirect()->route('pegawai.index')
                         ->with('success', "Pegawai \"{$pegawai->nama}\" berhasil diperbarui.");
    }

    public function destroy(Pegawai $pegawai)
    {
        $nama = $pegawai->nama;
        $this->pegawaiService->delete($pegawai);

        return redirect()->route('pegawai.index')
                         ->with('success', "Pegawai \"{$nama}\" berhasil dihapus.");
    }
}
