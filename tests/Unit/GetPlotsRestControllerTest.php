<?php

namespace Tests\Unit;

use App\Http\Controllers\API\GetPlotsRestController;
use App\Repositories\Read\PlotRepository;
use App\Services\GetPlotAction;
use App\Services\PlotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class GetPlotsRestControllerTest extends TestCase
{
    use RefreshDatabase;

    private $plotAction;
    private $plotRepository;
    private $plotService;
    private $getPlotsRestController;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->plotAction     = $this->createMock(GetPlotAction::class);
        $this->plotRepository = $this->createMock(PlotRepository::class);
        $this->plotService    = $this->createMock(PlotService::class);

        $this->getPlotsRestController = new GetPlotsRestController(
            $this->plotAction,
            $this->plotRepository,
            $this->plotService
        );
    }

    /**
     * @throws Exception
     */
    public function testShowMethodWithEmptyPlots()
    {
        $requestData = [
            'cadastreNumbers' => '123,456'
        ];
        $request     = $this->createMock(Request::class);
        $request->expects($this->once())
                ->method('toArray')
                ->willReturn($requestData);

        $this->plotService->expects($this->once())
                          ->method('plotsByCadastreNumbers')
                          ->willReturn(new Collection());

        $response = $this->getPlotsRestController->show($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['message' => 'Plot not found and failed to fetch from API'], $response->getData(true));
    }

    public function testShowMethodWithPlots()
    {
        $requestData = [
            'cadastreNumbers' => '123,456'
        ];
        $request     = $this->createMock(Request::class);
        $request->expects($this->once())
                ->method('toArray')
                ->willReturn($requestData);

        $plots = new Collection([['cadastreNumber' => '123', 'address' => 'Test Address']]);
        $this->plotService->expects($this->once())
                          ->method('plotsByCadastreNumbers')
                          ->willReturn($plots);

        $response = $this->getPlotsRestController->show($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['plots' => $plots->toArray()], $response->getData(true));
    }
}
