<?php
namespace Tests\Unit\Controllers;


use Tests\TestCase;
use Illuminate\Http\Request;
use App\Services\BusinessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Responses\BusinessResponse;
use App\Validator\RequestValidator;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\Admin\BusinessController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BusinessControllerTest extends TestCase
{
    public function makeController($validator = null, $service = null, $response = null)
    {
        $validator ??= $this->createStub(RequestValidator::class);
        $service ??= $this->createStub(BusinessService::class);
        $response ??= $this->createStub(BusinessResponse::class);

        return new BusinessController($validator, $service, $response);
    }

    public function test_index_mockService_getAllBusiness()
    {
        $mockService = $this->createMock(BusinessService::class);
        $mockService->expects($this->once())
            ->method('getAllBusiness')
            ->willReturn(new Collection);

        $controller = $this->makeController(null, $mockService);
        $controller->index();
    }

    public function test_index_mockResponse_getAllBusinesses()
    {
        $businesses = new Collection();

        $mockResponse = $this->createMock(BusinessResponse::class);
        $mockResponse->expects($this->once())
            ->method('getAllBusinesses')
            ->with($businesses);

        $stubService = $this->createStub(BusinessService::class);
        $stubService->method('getAllBusiness')
            ->willReturn($businesses);
        
        $controller = $this->makeController(null, $stubService, $mockResponse);
        $controller->index();
    }

    public function test_index_stubResponse_expected()
    {
        $businesses = new Collection();

        $expected = new JsonResponse();

        $stubResponse = $this->createStub(BusinessResponse::class);
        $stubResponse->method('getAllBusinesses')
            ->willReturn($expected);
        
        $stubService = $this->createStub(BusinessService::class);
        $stubService->method('getAllBusiness')
            ->willReturn($businesses);
        
        $controller = $this->makeController(null, $stubService, $stubResponse);
        $response = $controller->index();

        $this->assertEquals($expected, $response);
    }

    public function test_create_mockValidator_validateBusiness()
    {
        $request = new Request();

        $mockValidator = $this->createMock(RequestValidator::class);
        $mockValidator->expects($this->once())
            ->method('validateBusiness')
            ->with($request);

        $stubService = $this->createStub(BusinessService::class);
        $stubService->method('createBusiness')
            ->willReturn(new Collection());

        $controller = $this->makeController($mockValidator, $stubService);
        $controller->create($request);
    }

    public function test_create_mockService_createBusiness()
    {
        $request = new Request();

        $mockService = $this->createMock(BusinessService::class);
        $mockService->expects($this->once())
            ->method('createBusiness')
            ->with($request)
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $mockService);
        $controller->create($request);
    }

    public function test_create_mockResponse_createBusinessSuccess()
    {
        $request = new Request();
        $businesses = new Collection();

        $mockResponse = $this->createMock(BusinessResponse::class);
        $mockResponse->expects($this->once())
            ->method('createBusinessSuccess')
            ->with( $businesses);
        
        $stubService = $this->createStub(BusinessService::class);
        $stubService->method('createBusiness')
            ->willReturn($businesses);

        $controller = $this->makeController(null, $stubService, $mockResponse);
        $controller->create($request);
    }

    public function test_create_stubResponse_expected()
    {
        $request = new Request();
        $expected = new JsonResponse();

        $stubResponse = $this->createStub(BusinessResponse::class);
        $stubResponse->method('createBusinessSuccess')
            ->willReturn($expected);
        
        $stubService = $this->createStub(BusinessService::class);
        $stubService->method('createBusiness')
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $stubService, $stubResponse);
        $response = $controller->create($request);

        $this->assertEquals($expected, $response);
    }

    public function test_update_mockValidator_validateBusiness()
    {
        $request = new Request();
        $id = random_int(1,10);

        $mockValidator = $this->createMock(RequestValidator::class);
        $mockValidator->expects($this->once())
            ->method('validateBusiness')
            ->with($request);

        $stubService = $this->createStub(BusinessService::class);
        $stubService->method('updateBusiness')
            ->willReturn(new Collection());

        $controller = $this->makeController($mockValidator, $stubService);
        $controller->update($request, $id);
    }

    public function test_update_mockService_updateBusiness()
    {
        $request =  new Request();
        $id = random_int(1,10);

        $mockService =  $this->createMock(BusinessService::class);
        $mockService->expects($this->once())
            ->method('updateBusiness')
            ->with($request, $id);

        $controller = $this->makeController(null, $mockService);
        $controller->update($request, $id);
    }

    public function test_update_mockResponse_updateBusinessSuccess()
    {
        $request = new Request();
        $id = random_int(1,10);
        $businesses = new Collection();

        $mockResponse =  $this->createMock(BusinessResponse::class);
        $mockResponse->expects($this->once())
            ->method('updateBusinessSuccess')
            ->with($businesses);
        
        $stubService =  $this->createStub(BusinessService::class);
        $stubService->method('updateBusiness')
            ->willReturn($businesses);
        
        $controller = $this->makeController(null, $stubService, $mockResponse);
        $controller->update($request, $id);
    }

    public function test_update_stubResponse_expected()
    {
        $request = new Request();
        $id = random_int(1,10);
        $expected = new JsonResponse();

        $stubResponse = $this->createStub(BusinessResponse::class);
        $stubResponse->method('updateBusinessSuccess')
            ->willReturn($expected);

        $stubServices = $this->createStub(BusinessService::class);
        $stubServices->method('updateBusiness')
            ->willReturn(new Collection());
        
        $controller = $this->makeController(null, $stubServices, $stubResponse);
        $response = $controller->update($request, $id);

        $this->assertEquals($expected, $response);
    }

    public function test_delete_mockService_deleteBusiness()
    {
        $id = random_int(1,10);

        $mockService = $this->createMock(BusinessService::class);
        $mockService->expects($this->once())
            ->method('deleteBusiness')
            ->with($id);

        $controller = $this->makeController(null, $mockService);
        $controller->delete($id);
    }

    public function test_delete_mockResponse_deleteBusinessSuccess()
    {
        $id = random_int(1,10);

        $mockResponse = $this->createMock(BusinessResponse::class);
        $mockResponse->expects($this->once())
            ->method('deleteBusinessSuccess');
        
        $stubService = $this->createStub(BusinessService::class);
        $stubService->method('deleteBusiness')
            ->willReturn(new Collection());
        
        $controller = $this->makeController(null, $stubService, $mockResponse);
        $controller->delete($id);
    }

    public function test_delete_stubResponse_expected()
    {
        $id = random_int(1,10);
        $expected = new JsonResponse();

        $stubResponse = $this->createStub(BusinessResponse::class);
        $stubResponse->method('deleteBusinessSuccess')
            ->willReturn($expected);
        
        $stubService = $this->createStub(BusinessService::class);
        $stubService->method('deleteBusiness')
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $stubService, $stubResponse);
        $response = $controller->delete($id);

        $this->assertEquals($expected, $response);
    }
}
