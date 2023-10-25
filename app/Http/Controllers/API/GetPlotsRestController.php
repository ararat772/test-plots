<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Read\PlotRepository;
use App\Services\GetPlotAction;
use App\Services\PlotService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetPlotsRestController extends Controller
{
    public function __construct(
        protected GetPlotAction $plotAction,
        protected PlotRepository $eloquentPlotRepository,
        protected PlotService $plotService
    ) {
    }

    public function show(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $plots = $this->plotService->plotsByCadastreNumbers($data);

        if ($plots->isEmpty()) {
            return new JsonResponse(['message' => 'Plot not found and failed to fetch from API'], 404);
        }

        return new JsonResponse($plots);
    }
}
