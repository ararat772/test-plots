<?php

namespace Tests\Unit;

use App\Http\Controllers\WEB\WebController;
use App\Repositories\Read\PlotRepository;
use App\Services\GetPlotAction;
use App\Services\PlotService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class WebControllerTest extends TestCase
{
    use RefreshDatabase;

    private $plotAction;
    private $plotRepository;
    private $plotService;
    private $webController;
    private $view;
    private $viewFactory;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->plotAction     = $this->createMock(GetPlotAction::class);
        $this->plotRepository = $this->createMock(PlotRepository::class);
        $this->plotService    = $this->createMock(PlotService::class);

        $this->webController = new WebController(
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

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
                ->method('toArray')
                ->willReturn($requestData);

        $this->plotService->expects($this->once())
                          ->method('plotsByCadastreNumbers')
                          ->willReturn(new Collection());

        $this->view        = $this->createMock(View::class);
        $this->viewFactory = $this->createMock(Factory::class);
        $this->viewFactory->expects($this->any())
                          ->method('make')
                          ->withAnyParameters()
                          ->willReturn($this->view);
        $this->app->instance('view', $this->viewFactory);

        $response = $this->webController->show($request);

        $this->assertEquals($this->view, $response);
    }


    public function testShowMethodWithPlots()
    {
        $requestData = [
            'cadastreNumbers' => '123,456'
        ];

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
                ->method('toArray')
                ->willReturn($requestData);
        $plots = new Collection([['cadastreNumber' => '123', 'address' => 'Test Address']]);

        $this->plotService->expects($this->once())
                          ->method('plotsByCadastreNumbers')
                          ->willReturn($plots);

        $response = $this->webController->show($request);

        $this->assertEquals('plots', $response->getName());
        $this->assertEquals($plots, $response->getData()['plots']);
    }
}
