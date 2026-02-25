<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUnitRequest;
use App\Http\Requests\Admin\UpdateUnitRequest;
use App\Models\Unit;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::withCount('subUnits');

        if ($q = $request->input('q')) {
            $query->where('nama_unit', 'like', "%{$q}%");
        }

        $units = $query->orderBy('nama_unit')->paginate(15)->withQueryString();

        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(StoreUnitRequest $request)
    {
        $unit = Unit::create($request->validated());

        ActivityLogger::log(
            'TAMBAH UNIT',
            'Unit',
            $unit->id,
            "Nama: {$unit->nama_unit}"
        );

        return redirect()->route('admin.units.index')
                         ->with('success', "Unit \"{$unit->nama_unit}\" berhasil ditambahkan.");
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $old = $unit->nama_unit;
        $unit->update($request->validated());

        ActivityLogger::log(
            'EDIT UNIT',
            'Unit',
            $unit->id,
            "Dari: {$old} → {$unit->nama_unit}"
        );

        return redirect()->route('admin.units.index')
                         ->with('success', "Unit \"{$unit->nama_unit}\" berhasil diperbarui.");
    }

    public function destroy(Unit $unit)
    {
        if ($unit->subUnits()->exists()) {
            return redirect()->route('admin.units.index')
                             ->with('error', "Unit \"{$unit->nama_unit}\" tidak dapat dihapus karena masih memiliki sub unit.");
        }

        $nama = $unit->nama_unit;
        $id   = $unit->id;
        $unit->delete();

        ActivityLogger::log('HAPUS UNIT', 'Unit', $id, "Nama: {$nama}");

        return redirect()->route('admin.units.index')
                         ->with('success', "Unit \"{$nama}\" berhasil dihapus.");
    }
}
