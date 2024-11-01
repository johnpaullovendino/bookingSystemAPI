<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\BookWithPromoCode;

class BookingRepository
{
    private $bookWithPromo;

    public function __construct(BookWithPromoCode $bookWithPromo)
    {
        $this->bookWithPromo = $bookWithPromo;
    }
    
    public function getAllBookings()
    {
        $bookings = Booking::orderBy('created_at', 'desc')->get();

        return $bookings;
    }

    public function createBooking(Request $request)
    {
        $service = Service::find($request->service_id);
        
        if (!$service) {
            return response()->json([
               'success' => false,
               'message' => 'Service not found',
                'code' => 404
            ], 404);
        }

        $booking = new Booking();
        $booking->service_id = $service->id;
        $booking->duration = $request->duration;
        $booking->amount = $service->price;
        $booking->name = $request->name;
        $booking->phoneNumber = $request->phoneNumber;
        $booking->email = $request->email;
        $booking->promo = 0;
    
        $promoCode = $request->promo_code ?? '';
        $discount = 0;
    
        if ($this->bookWithPromo->hasPromoCode($promoCode)) {
            $booking->promo = 1;
            $discountPercentage = $this->bookWithPromo->getPromoCodes()[$promoCode];
            $discount = $booking->amount * ($discountPercentage / 100);
        }
    
        $booking->promo_code = $promoCode;
        $booking->discount = $discount;
        $booking->total_amount = $booking->amount - $discount;
    
        $booking->save();

        return $booking;
    }

    public function getBookingById($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'response_code' => 404,
                'message' => 'Booking not found',
            ], 404);
        }

        return $booking;
    }

    public function deleteBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return $booking;
    }

}
