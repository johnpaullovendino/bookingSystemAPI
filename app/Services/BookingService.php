<?php

namespace App\Services;

use App\Repositories\BookingRepository;

class BookingService
{
    private $repository;

    public function __construct(BookingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getBookings()
    {
        return $this->repository->getAllBookings();
    }

    public function createBooking($request)
    {
        return $this->repository->createBooking($request);
    }

    public function deleteBooking($id)
    {
        return $this->repository->deleteBooking($id);
    }
}
