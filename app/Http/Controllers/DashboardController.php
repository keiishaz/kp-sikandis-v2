<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService) {}

    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role && $user->role->nama_role === 'admin';
        
        if ($isAdmin) {
            $data = $this->dashboardService->getAdminSummary();
            $data['roleTitle'] = 'Admin';
        } else {
            $data = $this->dashboardService->getOperatorSummary();
            $data['roleTitle'] = 'Operator';
        }

        return view('dashboard', $data);
    }
}
