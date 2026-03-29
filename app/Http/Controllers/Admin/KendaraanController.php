<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\RoleRoutePrefix;
use App\Http\Requests\Admin\StoreKendaraanRequest;
use App\Http\Requests\Admin\UpdateKendaraanRequest;
use App\Models\Kendaraan;
use App\Services\KendaraanService;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    use RoleRoutePrefix;

    public function __construct(private readonly KendaraanService $kendaraanService) {}

    public function index(Request $request)
    {
        $data = $this->kendaraanService->list($request->only(['status', 'q']));

        return view('admin.kendaraan.index', $data);
    }

    public function print(Request $request)
    {
        $data = $this->kendaraanService->printData($request->only(['status', 'q', 'kategori_id', 'jenis_penggunaan', 'status_pajak']));

        return view('admin.kendaraan.print', $data);
    }

    public function printCount(Request $request)
    {
        $count = $this->kendaraanService->countForPrint($request->only(['status', 'q', 'kategori_id', 'jenis_penggunaan', 'status_pajak']));

        return response()->json(['count' => $count]);
    }

    public function create()
    {
        return view('admin.kendaraan.create', $this->kendaraanService->formData());
    }

    public function store(StoreKendaraanRequest $request)
    {
        $this->kendaraanService->store($request->validated());

        return redirect()->route($this->rp() . '.kendaraan.index')
                         ->with('success', 'Data Kendaraan berhasil ditambahkan beserta Token QR.');
    }

    public function show(Kendaraan $kendaraan)
    {
        $data = $this->kendaraanService->detail($kendaraan);

        return view('admin.kendaraan.show', $data);
    }

    public function edit(Kendaraan $kendaraan)
    {
        return view('admin.kendaraan.edit', array_merge(
            ['kendaraan' => $kendaraan],
            $this->kendaraanService->formData()
        ));
    }

    public function update(UpdateKendaraanRequest $request, Kendaraan $kendaraan)
    {
        $this->kendaraanService->update($kendaraan, $request->validated());

        return redirect()->route($this->rp() . '.kendaraan.index')
                         ->with('success', 'Data Kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $updated = $this->kendaraanService->toggleStatus($kendaraan);
        $msg     = $updated->status === 'nonaktif' ? 'dinonaktifkan' : 'diaktifkan';

        return redirect()->route($this->rp() . '.kendaraan.index')
                         ->with('success', "Status kendaraan berhasil {$msg}.");
    }
}
