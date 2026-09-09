<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardService $dashboardService)
    {
        return view('dashboard', $dashboardService->forUser($request->user()));
    }
}
