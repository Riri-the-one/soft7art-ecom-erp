<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index(Request $request, StatisticsService $statisticsService)
    {
        $request->validate(['filter' => ['nullable', 'in:all,today,week,month']]);
        $filter = $request->query('filter') ?: 'all';

        return view('statistics.index', $statisticsService->forFilter($filter) + ['filter' => $filter]);
    }
}
