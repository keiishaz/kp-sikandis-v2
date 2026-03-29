<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class AdminDashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService) {}

    public function index()
    {
        return view('admin.dashboard', $this->dashboardService->getSummary());
    }
}
