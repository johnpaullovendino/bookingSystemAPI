<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Services\BusinessService;
use Illuminate\Support\Collection;
use App\Repositories\BusinessRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BusinessServiceTest extends TestCase
{
    public function makeService($repository = null)
    {
        $repository ??= $this->createStub(BusinessRepository::class);

        return new BusinessService($repository);
    }

    public function test_getAllBusiness_mockBusinessRepository_getAllBusiness()
    {
        $mockBusinessRepository = $this->createMock(BusinessRepository::class);
        $mockBusinessRepository->expects($this->once())
            ->method('getAllBusiness')
            ->willReturn(new Collection());

        $service = $this->makeService($mockBusinessRepository);
        $service->getAllBusiness();
    }

    public function test_createBusiness_mockBusinessRepository_createBusiness()
    {
        $request = new Request([
            'name' => 'sampleName',
            'opening_hours' => 'sampleDate',
            'status' => 'open'
        ]);

        $mockBusinessRepository = $this->createMock(BusinessRepository::class);
        $mockBusinessRepository->expects($this->once())
            ->method('createBusiness')
            ->with($request)
            ->willReturn(new Collection());

        $service = $this->makeService($mockBusinessRepository);
        $service->createBusiness($request);
    }

    public function test_updateBusiness_mockBusinessRepository_updateBusiness()
    {
        $request = new Request([
            'name' => 'sampleName',
            'opening_hours' => 'sampleDate',
            'status' => 'open'
        ]);

        $id = 1;

        $mockBusinessRepository = $this->createMock(BusinessRepository::class);
        $mockBusinessRepository->expects($this->once())
            ->method('updateBusiness')
            ->with($request, $id)
            ->willReturn(new Collection());

        $service = $this->makeService($mockBusinessRepository);
        $service->updateBusiness($request, $id);
    }

    public function test_deleteBusiness_mockBusinessRepository_deleteBusiness()
    {
        $id = 1;

        $mockBusinessRepository = $this->createMock(BusinessRepository::class);
        $mockBusinessRepository->expects($this->once())
            ->method('deleteBusiness')
            ->with($id)
            ->willReturn('Business Deleted Success');

        $service = $this->makeService($mockBusinessRepository);
        $service->deleteBusiness($id);
    }
}
