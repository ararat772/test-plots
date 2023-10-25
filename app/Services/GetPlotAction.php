<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GetPlotAction
{
    public function run(array $plots): array
    {
        $response = Http::withHeaders(config('bigland.headers'))->post(config('bigland.base_url') . 'test/plots', [
            'collection' => [
                'plots' => $plots
            ]
        ]);

        return $response->json();
    }
}
