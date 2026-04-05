<?php

namespace App\Http\Controllers;

use App\Models\QrKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrKendaraanController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = \App\Models\Kategori::all();
        
        $query = QrKendaraan::with(['kendaraan.kategori'])
            ->whereHas('kendaraan', function ($q) {
                $q->where('status', 'aktif');
                
                $user = Auth::user();
                if ($user && $user->isOperator() && $user->unit_id) {
                    $q->where('unit_id', $user->unit_id);
                }
            });

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($qq) use ($search) {
                $qq->whereHas('kendaraan', function ($q) use ($search) {
                    $q->where('nama_kendaraan', 'like', "%{$search}%")
                      ->orWhere('no_polisi', 'like', "%{$search}%");
                })->orWhere('token', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->whereHas('kendaraan', fn($q) => $q->where('kategori_id', $request->kategori_id));
        }

        if ($request->filled('scan_status')) {
            if ($request->scan_status === 'never') $query->where('scan_count', 0);
            if ($request->scan_status === 'active') $query->where('scan_count', '>', 0);
        }

        $qrs = $query->orderByDesc('scan_count')->paginate(15)->withQueryString();

        return view('qr-kendaraan.index', compact('qrs', 'kategoris'));
    }
}
