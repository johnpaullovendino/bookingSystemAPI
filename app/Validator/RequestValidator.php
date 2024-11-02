<?php

namespace App\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidRequestException;

class RequestValidator
{
    /**
     * 
     * @param mixed $request
     * @param mixed $rules
     * @return void
     * 
     * @throws InvalidRequestException
     */
    
    
    private function validate(Request $request, array $rules): void
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            throw new InvalidRequestException($validator->errors()->first());
    }

    //Business
    public function validateBusiness(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'opening_hours' => 'required|string',
            'status' => 'required|string'
        ];

        $this->validate($request, $rules);
    }

    //Service
    public function validateService(Request $request): void
    {
        $rules = [
            'business_id' => 'required|integer',
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer'
        ];

        $this->validate($request, $rules);
    }

    //Bookings
    public function validateBookings(Request $request)
    {
        $rules = [
            'service_id' => 'required|integer',
            'duration' => 'required|string',
            'name'  =>  'required|string',
            'phoneNumber'  =>  'required|string',
            'email'  =>  'required|email',
            'amount' => 'required|integer',
            'promo' => 'required|boolean',
            'promo_code'  =>  'nullable|string',
            'discount' => 'nullable|integer',
            'total_amount' => 'nullable|integer'
        ];

        $this->validate($request, $rules);
    }

    //Reports
    public function validateReports(Request $request)
    {
        $rules = [
            'from_date' => 'required|string',
            'to_date' => 'required|string',
            'bookId' => 'sometimes|string',
            'serviceName' => 'sometimes|string',
        ];

        $this->validate($request, $rules);
    }
}
