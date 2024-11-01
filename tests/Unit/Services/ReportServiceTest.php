<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Services\ReportService;
use Illuminate\Support\Collection;
use App\Validator\ReportsValidator;
use App\Repositories\ReportsRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportServiceTest extends TestCase
{
    private function makeService($validator = null, $repository = null)
    {
        $validator ??= $this->createStub(ReportsValidator::class);
        $repository ??= $this->createMock(ReportsRepository::class);

        return new ReportService($validator, $repository);
    }

    public function test_getReports_mockValidator_validateDatesFormat()
    {
        $request = new Request([
            'from_date' => 'sampleDate',
            'to_date' => 'sampleEndDate',
            'bookId' => 'required',
            'serviceName' => 'required|sometimes',
        ]);

        $mockValidator = $this->createMock(ReportsValidator::class);
        $mockValidator->expects($this->once())
            ->method('validateDatesFormat')
            ->with([$request['from_date'], $request['to_date']]);
        
        $stubReportsRepo = $this->createStub(ReportsRepository::class);
        $stubReportsRepo->method('getReportsByDate')
            ->willReturn(new Collection());

        $service = $this->makeService($mockValidator, $stubReportsRepo);
        $service->getReports($request);
    }

    public function test_getReports_mockValidator_validateDateRange()
    {
        $request = new Request([
            'from_date' => 'sampleDate',
            'to_date' => 'sampleEndDate',
            'bookId' => 'required',
            'serviceName' => 'required|sometimes',
        ]);

        $mockValidator = $this->createMock(ReportsValidator::class);
        $mockValidator->expects($this->once())
            ->method('validateDateRange')
            ->with($request['from_date'], $request['to_date']);
        
        $stubReportsRepo = $this->createStub(ReportsRepository::class);
        $stubReportsRepo->method('getReportsByDate')
            ->willReturn(new Collection());

        $service = $this->makeService($mockValidator, $stubReportsRepo);
        $service->getReports($request);
    }

    public function test_getReports_mockReportsRepository_getReportsByDate()
    {
        $request = new Request([
            'from_date' => 'sampleDate',
            'to_date' => 'sampleEndDate',
            'bookId' => 'required',
            'serviceName' => 'required|sometimes',
        ]);

        $mockReportsRepository = $this->createMock(ReportsRepository::class);
        $mockReportsRepository->expects($this->once())
            ->method('getReportsByDate')
            ->willReturn(new Collection());
        
        $service = $this->makeService(null, $mockReportsRepository);
        $service->getReports($request);
    }
}
