<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BookingService;
use App\Responses\BookingResponse;
use App\Validator\RequestValidator;


class BookingsController extends Controller
{
    private $validator, $service, $response;

    public function __construct(RequestValidator $request, BookingService $service, BookingResponse $response)
    {
        $this->validator = $request;
        $this->service = $service;
        $this->response = $response;
    }

    public function index()
    {
        $bookings = $this->service->getBookings();

        return $this->response->allBookings($bookings);
    }

    public function create(Request $request)
    {
        $this->validator->validateBookings($request);

        $createdBooking = $this->service->createBooking($request);

        return $this->response->createBookingSuccess($createdBooking);
    }

    public function show($id)
    {
        $booking = $this->service->getBooking($id);

        return $this->response->getBookingSuccess($booking);
    }

    public function delete($id)
    {
        $this->service->deleteBooking($id);

        return $this->response->deleteBookingSuccess();
    }
}
