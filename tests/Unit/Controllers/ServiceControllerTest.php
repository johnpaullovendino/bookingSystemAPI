<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Services\ServiceService;
use App\Responses\ServiceResponse;
use Illuminate\Support\Collection;
use App\Validator\RequestValidator;
use App\Http\Controllers\Business\ServiceController;

class ServiceControllerTest extends TestCase
{
    public function makeController($validator = null, $service = null, $response = null)
    {
        $validator ??= $this->createStub(RequestValidator::class);
        $service ??= $this->createStub(ServiceService::class);
        $response ??= $this->createStub(ServiceResponse::class);

        return new ServiceController($validator, $service, $response);
    }
    
    public function test_index_mockService_getAllServices()
    {
        $mockService =  $this->createMock(ServiceService::class);
        $mockService->expects($this->once())
            ->method('getAllServices')
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $mockService);
        $controller->index();
    }

    public function test_index_mockResponse_allServices()
    {
        $services = new Collection();

        $mockResponse = $this->createMock(ServiceResponse::class);
        $mockResponse->expects($this->once())
            ->method('allServices')
            ->with($services);
        
        $stubService = $this->createStub(ServiceService::class);
        $stubService->method('getAllServices')
            ->willReturn($services);

        $controller = $this->makeController(null,  $stubService, $mockResponse);
        $controller->index();
    }

    public function test_index_stubResponse_expected()
    {
        $expected = new Collection();

        $stubResponse = $this->createStub(ServiceResponse::class);
        $stubResponse->method('allServices')
            ->willReturn($expected);
        
        $stubService = $this->createStub(ServiceService::class);
        $stubService->method('getAllServices')
            ->willReturn(new Collection());
        
        $controller = $this->makeController(null,  $stubService, $stubResponse);
        $response = $controller->index();

        $this->assertSame($expected, $response);
    }

    public function test_create_mockValidator_validateService()
    {
        $request = new Request();
        
        $mockValidator = $this->createMock(RequestValidator::class);
        $mockValidator->expects($this->once())
            ->method('validateService')
            ->with($request);
    
        $stubService = $this->createStub(ServiceService::class);
        $stubService->method('createService')
            ->willReturn(new Collection());

        $controller = $this->makeController($mockValidator, $stubService);
        $controller->create($request);
    }

    public function test_create_mockService_createService()
    {
        $request = new Request();

        $mockService = $this->createMock(ServiceService::class);
        $mockService->expects($this->once())
            ->method('createService')
            ->with($request);
        
        $controller = $this->makeController(null, $mockService);
        $controller->create($request);
    }

    public function test_create_mockResponse_createServiceSuccess()
    {
        $request = new Request();
        $services = new Collection();

        $mockResponse = $this->createMock(ServiceResponse::class);
        $mockResponse->expects($this->once())
            ->method('createServiceSuccess')
            ->with($services);
        
        $stubService = $this->createStub(ServiceService::class);
        $stubService->method('createService')
            ->willReturn($services);

        $controller = $this->makeController(null, $stubService, $mockResponse);
        $controller->create($request);
    }

    public function test_create_stubResponse_expected()
    {
        $request = new Request();
        $expected = new Collection();

        $stubResponse = $this->createStub(ServiceResponse::class);
        $stubResponse->method('createServiceSuccess')
            ->willReturn($expected);
        
        $stubService = $this->createStub(ServiceService::class);
        $stubService->method('createService')
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $stubService, $stubResponse);
        $response = $controller->create($request);

        $this->assertSame($expected, $response);
    }

    public function test_update_mockValidator_validateService()
    {
        $request = new Request();
        $id = random_int(1, 10);
        
        $mockValidator = $this->createMock(RequestValidator::class);
        $mockValidator->expects($this->once())
            ->method('validateService')
            ->with($request);
        
        $stubService = $this->createStub(ServiceService::class);
        $stubService->method('updateService')
            ->willReturn(new Collection());
        
        $controller = $this->makeController($mockValidator, $stubService);
        $controller->update($request, $id);
    }

    public function test_update_mockService_updateService()
    {
        $request = new Request();
        $id = random_int(1, 10);

        $mockService = $this->createMock(ServiceService::class);
        $mockService->expects($this->once())
            ->method('updateService')
            ->with($request, $id);
        
        $controller = $this->makeController(null, $mockService);
        $controller->update($request, $id);
    }

    public function test_update_mockResponse_updateServiceSuccess()
    {
        $request = new Request();
        $id = random_int(1, 10);
        $services = new Collection();

        $mockResponse = $this->createMock(ServiceResponse::class);
        $mockResponse->expects($this->once())
            ->method('updateServiceSuccess')
            ->with($services);

        $stubService = $this->createStub(ServiceService::class);
        $stubService->method('updateService')
            ->willReturn($services);
        
        $controller = $this->makeController(null, $stubService, $mockResponse);
        $controller->update($request, $id);
    }

    public function test_update_stubResponse_expected()
    {
        $request = new Request();
        $id = random_int(1, 10);
        $expected = new Collection();

        $stubResponse = $this->createStub(ServiceResponse::class);
        $stubResponse->method('updateServiceSuccess')
            ->willReturn($expected);

        $stubService = $this->createStub(ServiceService::class);
        $stubService->method('updateService')
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $stubService, $stubResponse);
        $response = $controller->update($request, $id);

        $this->assertSame($expected, $response);
    }

    public function test_delete_mockService_deleteService()
    {
        $id = random_int(1, 10);

        $mockService = $this->createMock(ServiceService::class);
        $mockService->expects($this->once())
            ->method('deleteService')
            ->with($id);

        $controller = $this->makeController(null, $mockService);
        $controller->delete($id);
    }

    public function test_delete_mockResponse_deleteServiceSuccess()
    {
        $id = random_int(1, 10);

        $mockResponse = $this->createMock(ServiceResponse::class);
        $mockResponse->expects($this->once())
            ->method('deleteServiceSuccess');

        $stubService = $this->createStub(ServiceService::class);
        $stubService->method('deleteService')
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $stubService, $mockResponse);
        $controller->delete($id);
    }

    public function test_delete_stubResponse_expected()
    {
        $id = random_int(1, 10);
        $expected = new Collection();

        $stubResponse = $this->createStub(ServiceResponse::class);
        $stubResponse->method('deleteServiceSuccess')
            ->willReturn($expected);

        $stubService = $this->createStub(ServiceService::class);
        $stubService->method('deleteService')
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $stubService, $stubResponse);
        $response = $controller->delete($id);

        $this->assertSame($expected, $response);
    }
}
