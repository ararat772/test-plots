<?php

namespace App\Services;

use App\Repositories\Read\PlotRepository;
use Illuminate\Support\Collection;

class PlotService
{
    public function __construct(
        protected PlotRepository $plotRepository,
        protected GetPlotAction $plotAction,
    ) {
    }

    public function plotsByCadastreNumbers(array $data): Collection
    {
        $cadastreNumbers = isset($data['cadastreNumbers']) ? explode(',', $data['cadastreNumbers']) : [];

        $plots = $this->plotRepository->findByCadastreNumbers($cadastreNumbers);

        $lastUpdated = $this->plotRepository->getMaxUpdatedAt();

        $oneHourAgo  = now()->subHour();

        if ($plots->isEmpty() || $lastUpdated <= $oneHourAgo) {
            $plotData = $this->plotAction->run($cadastreNumbers);

            if (isset($plotData)) {
                $this->saveDataToDatabase($plotData);
                $plots = $this->plotRepository->findByCadastreNumbers($cadastreNumbers);
            }
        }

        return $plots;
    }

    public function saveDataToDatabase(array $apiData): void
    {
        foreach ($apiData as $plotData) {
            if (isset($plotData['attrs'])) {
                $data = [
                    'cadastreNumber' => $plotData['attrs']['plot_number'],
                    'address'        => $plotData['attrs']['plot_address'],
                    'price'          => $plotData['attrs']['plot_price'],
                    'area'           => $plotData['attrs']['plot_area']
                ];
                $this->plotRepository->updateOrCreatePlot(['cadastreNumber' => $plotData['attrs']['plot_number']], $data);
            }
        }
    }
}
