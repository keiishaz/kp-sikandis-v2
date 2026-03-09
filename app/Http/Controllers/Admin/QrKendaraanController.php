<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrKendaraan;
use Illuminate\Http\Request;

class QrKendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = QrKendaraan::with(['kendaraan.kategori'])
            ->whereHas('kendaraan', fn($q) => $q->where('status', 'aktif'));

        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->where('nama_kendaraan', 'like', "%{$search}%")
                  ->orWhere('no_polisi', 'like', "%{$search}%");
            })->orWhere('token', 'like', "%{$search}%");
        }

        $qrs = $query->orderByDesc('scan_count')->paginate(15)->withQueryString();

        return view('admin.qr-kendaraan.index', compact('qrs'));
    }
}
