<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use App\Responses\BookingResponse;
use Illuminate\Support\Collection;
use App\Validator\RequestValidator;
use App\Http\Controllers\BookingsController;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingControllerTest extends TestCase
{
    public function makeController($validator = null, $service = null, $response = null)
    {
        $validator ??= $this->createStub(RequestValidator::class); 
        $service ??= $this->createStub(BookingService::class); 
        $response ??= $this->createStub(BookingResponse::class); 

        return new BookingsController($validator, $service, $response);
    }

    public function test_index_mockService_getBookings()
    {
        $mockService = $this->createMock(BookingService::class);
        $mockService->expects($this->once())
            ->method('getBookings')
            ->willReturn(new Collection());
        
        $controller = $this->makeController(null, $mockService);
        $controller->index();
    }

    public function test_index_mockResponse_allBookings()
    {
        $bookings = new Collection();

        $mockResponse = $this->createMock(BookingResponse::class);
        $mockResponse->expects($this->once())
            ->method('allBookings')
            ->willReturn($bookings);

        $stubService = $this->createStub(BookingService::class);
        $stubService->method('getBookings')
            ->willReturn($bookings);
        
        $controller = $this->makeController(null, $stubService, $mockResponse);
        $controller->index();
    }
    
    public function test_index_stubResponse_expected()
    {
        $bookings = new Collection();
        $expected = new JsonResponse();

        $stubResponse = $this->createStub(BookingResponse::class);
        $stubResponse->method('allBookings')
            ->willReturn($expected);
        
        $stubService = $this->createStub(BookingService::class);
        $stubService->method('getBookings')
            ->willReturn($bookings);

        $controller = $this->makeController(null, $stubService, $stubResponse);
        $response = $controller->index();

        $this->assertEquals($expected, $response);
    }

    public function test_create_mockValidator_validateBookings()
    {
        $request = new Request();
        
        $mockValidator = $this->createMock(RequestValidator::class);
        $mockValidator->expects($this->once())
            ->method('validateBookings')
            ->with($request);

        $stubService = $this->createStub(BookingService::class);
        $stubService->method('createBooking')
            ->willReturn(new Collection());
        
        $controller = $this->makeController($mockValidator, $stubService);
        $controller->create($request);
    }

    public function test_create_mockService_createBooking()
    {
        $request = new Request();

        $mockService = $this->createMock(BookingService::class);
        $mockService->expects($this->once())
            ->method('createBooking')
            ->with($request);
        
        $controller = $this->makeController(null, $mockService);
        $controller->create($request);
    }

    public function test_create_mockResponse_createBookingSuccess()
    {
        $request = new Request();
        $booking = new Collection();

        $mockResponse = $this->createMock(BookingResponse::class);
        $mockResponse->expects($this->once())
            ->method('createBookingSuccess')
            ->with($booking);
        
        $stubService = $this->createStub(BookingService::class);
        $stubService->method('createBooking')
            ->willReturn($booking);
        
        $controller = $this->makeController(null, $stubService, $mockResponse);
        $response = $controller->create($request);
    }

    public function test_create_stubResponse_expected()
    {
        $request = new Request();
        $expected = new JsonResponse();
        $booking = new Collection();

        $stubResponse = $this->createStub(BookingResponse::class);
        $stubResponse->method('createBookingSuccess')
            ->willReturn($expected);
        
        $stubService = $this->createStub(BookingService::class);
        $stubService->method('createBooking')
            ->willReturn($booking);
        
        $controller = $this->makeController(null, $stubService, $stubResponse);
        $response = $controller->create($request);

        $this->assertEquals($expected, $response);
    }

    public function test_delete_mockService_deleteBooking()
    {
        $id = random_int(1, 10);

        $mockService = $this->createMock(BookingService::class);
        $mockService->expects($this->once())
            ->method('deleteBooking')
            ->with($id);
        
        $controller = $this->makeController(null, $mockService);
        $controller->delete($id);
    }

    public function test_delete_mockResponse_deleteBookingSuccess()
    {
        $id = random_int(1, 10);

        $mockResponse = $this->createMock(BookingResponse::class);
        $mockResponse->expects($this->once())
            ->method('deleteBookingSuccess');

        $stubService = $this->createStub(BookingService::class);
        $stubService->method('deleteBooking')
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $stubService, $mockResponse);
        $controller->delete($id);
    }

    public function test_delete_stubResponse_expected()
    {
        $id = random_int(1, 10);
        $expected = new JsonResponse();

        $stubResponse = $this->createStub(BookingResponse::class);
        $stubResponse->method('deleteBookingSuccess')
            ->willReturn($expected);
        
        $stubService = $this->createStub(BookingService::class);
        $stubService->method('deleteBooking')
            ->willReturn(new Collection());

        $controller = $this->makeController(null, $stubService, $stubResponse);
        $response = $controller->delete($id);

        $this->assertEquals($expected, $response);
    }
}
