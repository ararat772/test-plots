<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Repositories\Read\PlotRepository;
use App\Services\GetPlotAction;
use App\Services\PlotService;
use Symfony\Component\HttpFoundation\Request;

class WebController extends Controller
{
    public function __construct(
        protected GetPlotAction $plotAction,
        protected PlotRepository $eloquentPlotRepository,
        protected PlotService $plotService
    ) {
    }

    public function show(Request $request)
    {
        $data = $request->toArray();

        $plots = $this->plotService->plotsByCadastreNumbers($data);

        if ($plots->isEmpty()) {
            return view('error', ['message' => 'Plot not found and failed to fetch from API']);
        }

        return view('plots', ['plots' => $plots]);
    }
}
