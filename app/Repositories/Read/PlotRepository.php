<?php

namespace App\Repositories\Read;

use App\Models\Plot;
use App\Repositories\Plot\PlotRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PlotRepository implements PlotRepositoryInterface
{
    private function query(): Builder
    {
        return Plot::query();
    }

    public function findByCadastreNumbers(array $cadastreNumbers): Collection
    {
        return $this->query()
                    ->whereIn('cadastreNumber', $cadastreNumbers)
                    ->get();
    }

    public function getMaxUpdatedAt(): ?string
    {
        return $this->query()->max('updated_at');
    }

    public function updateOrCreatePlot(array $attributes, array $values): void
    {
        $this->query()->updateOrCreate($attributes, $values);
    }
}
