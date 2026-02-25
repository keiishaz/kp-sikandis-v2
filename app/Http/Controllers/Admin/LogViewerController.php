<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LogReaderService;
use Illuminate\Http\Request;

class LogViewerController extends Controller
{
    protected $logService;

    public function __construct(LogReaderService $logService)
    {
        $this->logService = $logService;
    }

    public function aktivitas(Request $request)
    {
        $q = $request->input('q');
        $date = $request->input('date'); // Format 'Y-m-d'
        $page = $request->input('page', 1);

        $logs = $this->logService->readAktivitasLog($q, $date, 15, $page);

        return view('admin.log.aktivitas', compact('logs'));
    }

    public function login(Request $request)
    {
        $q = $request->input('q');
        $date = $request->input('date');
        $page = $request->input('page', 1);

        $logs = $this->logService->readLoginLog($q, $date, 15, $page);

        return view('admin.log.login', compact('logs'));
    }
}
