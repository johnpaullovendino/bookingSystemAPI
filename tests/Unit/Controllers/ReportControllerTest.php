<?php

namespace Tests\Feature\Unit\Controllers;

use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Responses\ReportResponse;
use App\Validator\RequestValidator;
use App\Http\Controllers\Reports\ReportsController;
use Illuminate\Http\JsonResponse;

class ReportControllerTest extends TestCase
{
    private function makeController($validator = null, $service = null, $response = null)
    {   
        $validator ??= $this->createStub(RequestValidator::class);
        $service ??= $this->createStub(ReportService::class);
        $response ??= $this->createStub(ReportResponse::class);

        return new ReportsController($validator, $service, $response);
    }

    public function test_getReports_mockValidator_validateReports()
    {
        $request = new Request();

        $mockValidator = $this->createMock(RequestValidator::class);
        $mockValidator->expects($this->once())
            ->method('validateReports')
            ->with($request);
        
        $stubService = $this->createStub(ReportService::class);
        $stubService->method('getReports')
            ->willReturn(new Collection());

        $controller = $this->makeController($mockValidator, $stubService);
        $controller->getReports($request);
    }

    public function test_getReports_mockService_getReports()
    {
        $request = new Request();

        $mockService = $this->createMock(ReportService::class);
        $mockService->expects($this->once())
            ->method('getReports')
            ->with($request)
            ->willReturn(new Collection());
        
        $controller = $this->makeController(null, $mockService);
        $controller->getReports($request);
    }

    public function test_getReports_mockResponse_reports()
    {
        $request = new Request();

        $reports = new Collection();

        $mockResponse = $this->createMock(ReportResponse::class);
        $mockResponse->expects($this->once())
            ->method('reports')
            ->with($request, $reports);
        
        $stubService = $this->createStub(ReportService::class);
        $stubService->method('getReports')
            ->willReturn($reports);
        
        $controller = $this->makeController(null, $stubService, $mockResponse);
        $controller->getReports($request);
    }

    public function test_getReports_stubResponse_expected()
    {
        $request = new Request();

        $expected = new JsonResponse();

        $stubResponse = $this->createStub(ReportResponse::class);
        $stubResponse->method('reports')
            ->willReturn($expected);

        $stubService = $this->createStub(ReportService::class);
        $stubService->method('getReports')
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $stubService, $stubResponse);
        $response = $controller->getReports($request);

        $this->assertEquals($expected, $response);
    }
}
