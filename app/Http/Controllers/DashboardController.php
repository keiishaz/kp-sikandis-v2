<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role && $user->role->nama_role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role && $user->role->nama_role === 'operator') {
            return redirect()->route('operator.dashboard');
        }

        return view('dashboard');
    }
}
