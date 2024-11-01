<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Services\ServiceService;
use Illuminate\Support\Collection;
use App\Repositories\ServiceRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceServiceTest extends TestCase
{
    public function makeService($repository = null)
    {
        $repository ??= $this->createStub(ServiceRepository::class);

        return new ServiceService($repository);
    }

    public function test_getAllServices_mockServiceRepository_getAllServicesRepo()
    {
        $mockServiceRepository = $this->createMock(ServiceRepository::class);
        $mockServiceRepository->expects($this->once())
            ->method('getAllServicesRepo')
            ->willReturn(new Collection());

        $service = $this->makeService($mockServiceRepository);
        $service->getAllServices();
    }

    public function test_createService_mockServiceRepository_createService()
    {
        $request = new Request([
            'business_id' => 1,
            'name' => 'sample_name',
            'description' => 'sample Description',
            'price' => 1000
        ]);

        $mockServiceRepository = $this->createMock(ServiceRepository::class);
        $mockServiceRepository->expects($this->once())
            ->method('createService')
            ->with($request)
            ->willReturn(new Collection());

        $service = $this->makeService($mockServiceRepository);
        $service->createService($request);
    }

    public function test_updateService_mockServiceRepository_updateService()
    {
        $id = 1;

        $request = new Request([
            'business_id' => $id,
            'name' => 'updated_name',
            'description' => 'updated Description',
            'price' => 1300
        ]);

        $mockServiceRepository = $this->createMock(ServiceRepository::class);
        $mockServiceRepository->expects($this->once())
            ->method('updateService')
            ->with($request, $id)
            ->willReturn(new Collection());

        $service = $this->makeService($mockServiceRepository);
        $service->updateService($request, $id);
    }

    public function test_deleteService_mockServiceRepository_deleteService()
    {
        $id = 1;

        $mockServiceRepository = $this->createMock(ServiceRepository::class);
        $mockServiceRepository->expects($this->once())
            ->method('deleteService')
            ->with($id)
            ->willReturn(new Collection());

        $service = $this->makeService($mockServiceRepository);
        $service->deleteService($id);
    }
}
