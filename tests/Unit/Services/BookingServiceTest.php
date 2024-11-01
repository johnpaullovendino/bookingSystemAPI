<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Services\BookingService;
use Illuminate\Support\Collection;
use App\Repositories\BookingRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingServiceTest extends TestCase
{
    private function makeService($repository = null)
    {
        $repository ??= $this->createStub(BookingRepository::class);

        return new BookingService($repository);
    }

    public function test_getBookings_mockBookingRepository_getAllBookings()
    {
        $mockBookingRepository = $this->createMock(BookingRepository::class);
        $mockBookingRepository->expects($this->once())
            ->method('getAllBookings')
            ->willReturn(new Collection());
        
        $service = $this->makeService($mockBookingRepository);
        $service->getBookings();
    }

    public function test_createBooking_mockBookingRepository_createBooking()
    {
        $request = new Request([
            'service_id' => 1,
            'duration' => 'sample_duration',
            'name'  =>  'sample_name',
            'phoneNumber'  =>  'sample_number',
            'email'  =>  'sample@gmail.com',
            'amount' => 'required|integer',
            'promo' => 0,
            'promo_code'  =>  null,
            'discount' => null,
            'total_amount' => null
        ]);

        $mockBookingRepository = $this->createMock(BookingRepository::class);
        $mockBookingRepository->expects($this->once())
            ->method('createBooking')
            ->with($request)
            ->willReturn(new Collection());

        $service = $this->makeService($mockBookingRepository);
        $service->createBooking($request);
    }

    public function test_deleteBooking_mockBookingRepository_deleteBooking()
    {
        $id = 1;

        $mockBookingRepository = $this->createMock(BookingRepository::class);
        $mockBookingRepository->expects($this->once())
            ->method('deleteBooking')
            ->with($id)
            ->willReturn(new Collection());

        $service = $this->makeService($mockBookingRepository);
        $service->deleteBooking($id);
    }
}
