<?php

namespace App\Console\Commands;

use App\Repositories\Read\PlotRepository;
use App\Services\GetPlotAction;
use App\Services\PlotService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class PlotDataFetcher extends Command
{
    protected $signature = 'plot:fetch';
    protected $description = 'Fetch plot data from the API and update database';

    protected $plotAction;
    protected $plotRepository;
    protected $plotService;

    public function __construct(
        GetPlotAction $plotAction,
        PlotRepository $plotRepositoryRepository,
        PlotService $plotService
    ) {
        parent::__construct();

        $this->plotAction     = $plotAction;
        $this->plotRepository = $plotRepositoryRepository;
        $this->plotService    = $plotService;
    }

    public function handle()
    {
        $this->info('Fetching plot data...');

        $cadastreNumbersInput = $this->ask('Please enter cadastre numbers (comma separated):');
        $cadastreNumbers      = explode(',', $cadastreNumbersInput);

        $plots = $this->plotsByCadastreNumbers(['cadastreNumbers' => $cadastreNumbers]);

        if ($plots->isEmpty()) {
            $this->error('Plot not found and failed to fetch from API.');
        } else {
            $this->info('Data has been successfully fetched and updated.');
        }
    }

    public function plotsByCadastreNumbers(array $data): Collection
    {
        $cadastreNumbers = isset($data['cadastreNumbers']) ? explode(',', $data['cadastreNumbers']) : [];

        $plots = $this->plotRepository->findByCadastreNumbers($cadastreNumbers);

        $lastUpdated = $this->plotRepository->getMaxUpdatedAt();
        $oneHourAgo  = now()->subHour();

        if ($plots->isEmpty() || $lastUpdated <= $oneHourAgo) {
            $plotData = $this->plotAction->run(['cadastreNumbers' => implode(',', $cadastreNumbers)]);

            if (isset($plotData)) {
                $this->plotService->saveDataToDatabase($plotData);
                $plots = $this->plotRepository->findByCadastreNumbers($cadastreNumbers);
            }
        }

        return $plots;
    }
}
