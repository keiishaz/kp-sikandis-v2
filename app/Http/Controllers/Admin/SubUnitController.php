<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubUnitRequest;
use App\Http\Requests\Admin\UpdateSubUnitRequest;
use App\Models\SubUnit;
use App\Models\Unit;
use App\Services\ActivityLogger;

class SubUnitController extends Controller
{
    public function index(Unit $unit)
    {
        $subUnits = $unit->subUnits()->orderBy('nama_sub_unit')->paginate(15);

        return view('admin.units.sub-units.index', compact('unit', 'subUnits'));
    }

    public function store(StoreSubUnitRequest $request, Unit $unit)
    {
        $subUnit = $unit->subUnits()->create($request->validated());

        ActivityLogger::log(
            'TAMBAH SUB UNIT',
            'SubUnit',
            $subUnit->id,
            "Nama: {$subUnit->nama_sub_unit} | Unit: {$unit->nama_unit}"
        );

        return redirect()->route('admin.units.sub-units.index', $unit)
                         ->with('success', "Sub unit \"{$subUnit->nama_sub_unit}\" berhasil ditambahkan.");
    }

    public function edit(Unit $unit, SubUnit $sub_unit)
    {
        return view('admin.units.sub-units.edit', compact('unit', 'sub_unit'));
    }

    public function update(UpdateSubUnitRequest $request, Unit $unit, SubUnit $sub_unit)
    {
        $old = $sub_unit->nama_sub_unit;
        $sub_unit->update($request->validated());

        ActivityLogger::log(
            'EDIT SUB UNIT',
            'SubUnit',
            $sub_unit->id,
            "Dari: {$old} → {$sub_unit->nama_sub_unit} | Unit: {$unit->nama_unit}"
        );

        return redirect()->route('admin.units.sub-units.index', $unit)
                         ->with('success', "Sub unit \"{$sub_unit->nama_sub_unit}\" berhasil diperbarui.");
    }

    public function destroy(Unit $unit, SubUnit $sub_unit)
    {
        $nama = $sub_unit->nama_sub_unit;
        $id   = $sub_unit->id;
        $sub_unit->delete();

        ActivityLogger::log(
            'HAPUS SUB UNIT',
            'SubUnit',
            $id,
            "Nama: {$nama} | Unit: {$unit->nama_unit}"
        );

        return redirect()->route('admin.units.sub-units.index', $unit)
                         ->with('success', "Sub unit \"{$nama}\" berhasil dihapus.");
    }
}
