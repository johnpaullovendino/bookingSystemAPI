<?php

namespace App\Responses;

use App\Libraries\LaravelResponse;
use App\Models\BookWithPromoCode;

class BookingResponse
{
    private $lib;

    private $bookWithPromo;

    public function __construct(LaravelResponse $lib, BookWithPromoCode $bookWithPromo)
    {
        $this->lib = $lib;
        $this->bookWithPromo = $bookWithPromo;
    }

    private function getPromoDetails($booking)
    {
        $promoDetails = [];

        if($this->bookWithPromo->hasPromoCode($booking->promo_code) === true) {
            
            $promoDetails['promo_code'] = $booking->promo_code;
            $promoDetails['discount'] = $booking->discount;
            $promoDetails['total_amount'] = $booking->total_amount;
        } else {
            $promoDetails['promo_code'] = '';
            $promoDetails['discount'] = 0;
            $promoDetails['total_amount'] = $booking->amount;
        }

        return $promoDetails;
    }

    public function allBookings($bookings)
    {
        foreach ($bookings as $booking) { 
            $bookingInfo[] = [
                'book_id' => $booking->book_id,
                'service_id' => $booking->service_id,
                'duration' => $booking->duration,
                'name' => $booking->name,
                'phoneNumber' => $booking->phoneNumber,
                'email' => $booking->email,
                'amount' => $booking->amount,
                'promo' => $booking->promo,
                'promoDetails' => (object) $this->getPromoDetails($booking)
            ];
        }

        return $this->lib->json([
            'success' => true,
            'message' => 'Bookings Retrieved Successfully',
            'code' => 200,
            'total_bookings' => count($bookings),
            'data' => $bookingInfo ?? []
        ]);
    }

    public function createBookingSuccess($createdBooking)
    {
        $bookingInfo = [
            'service_id' => $createdBooking->service_id,
            'duration' => $createdBooking->duration,
            'name' => $createdBooking->name,
            'phoneNumber' => $createdBooking->phoneNumber,
            'email' => $createdBooking->email,
            'amount' => $createdBooking->amount,
            'promo' => $createdBooking->promo,
            'promo_code' => $createdBooking->promo_code,
            'discount' => $createdBooking->discount,
            'total_amount' => $createdBooking->total_amount,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'code' => 201,
            'data' => $bookingInfo ?? []
        ]);
    }

    public function getBookingSuccess($booking)
    {
        $bookingInfo = [
            'book_id' => $booking->book_id,
            'service_id' => $booking->service_id,
            'duration' => $booking->duration,
            'name' => $booking->name,
            'phoneNumber' => $booking->phoneNumber,
            'email' => $booking->email,
            'amount' => $booking->amount,
            'promo' => $booking->promo,
            'promoDetails' => (object) $this->getPromoDetails($booking)
        ];

        return response()->json([
            'success' => true,
            'message' => 'Booking retrieved successfully',
            'code' => 200,
            'data' => $bookingInfo?? []
        ]);
    }

    public function deleteBookingSuccess()
    {
        return response()->json([
            'success' => true,
            'message' => 'Booking Deleted Successfully',
            'code' => 200,
            'data' => null
        ]);
    }

    public function error(string $code, string $message = '')
    {
        return $this->lib->json([
            'error_code' => $code,
            'error_message' => $message
        ]);
    }
}
