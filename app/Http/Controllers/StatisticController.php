<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index(Request $request, StatisticsService $statisticsService)
    {
        $filter = $request->query('filter', 'all');

        return view('statistics.index', $statisticsService->forFilter($filter) + ['filter' => $filter]);
    }
}
