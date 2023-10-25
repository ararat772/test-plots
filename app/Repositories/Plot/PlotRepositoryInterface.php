<?php

namespace App\Repositories\Plot;

use App\Models\Plot;
use Illuminate\Support\Collection;

interface PlotRepositoryInterface
{
    public function findByCadastreNumbers(array $cadastreNumbers): Collection;

    public function updateOrCreatePlot(array $attributes, array $values): void;

    public function getMaxUpdatedAt(): ?string;

}
