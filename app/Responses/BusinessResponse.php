<?php

namespace App\Responses;

use App\Libraries\LaravelResponse;

class BusinessResponse
{
    private $lib;

    public function __construct(LaravelResponse $lib)
    {
        $this->lib = $lib;
    }

    public function getAllBusinesses($businesses)
    {
        foreach ($businesses as $business) {
            $businessInfo[] = [
                'id' => $business->id,
                'name' => $business->name,
                'opening_hours' => $business->opening_hours,
                'status' => $business->status
            ];
        }

        return $this->lib->json([
            'success' => true,
            'message' => 'Businesses Retrieved Successfully',
            'code' => 200,
            'total_businesses' => count($businesses),
            'data' => $businessInfo ?? []
        ]);
    }
    
    public function createBusinessSuccess($business)
    {
        $businessInfo = [
            'id' => $business->id,
            'name' => $business->name,
            'opening_hours' => $business->opening_hours,
            'status' => $business->status
        ];

        return $this->lib->json([
            'success' => true,
            'message' => 'Business Created Successfully',
            'code' => 200,
            'data' => $businessInfo
        ]);
    }

    public function updateBusinessSuccess($business)
    {
        $businessInfo = [
            'id' => $business->id,
            'name' => $business->name,
            'opening_hours' => $business->opening_hours,
            'status' => $business->status
        ];

        return $this->lib->json([
            'success' => true,
            'message' => 'Business Updated Successfully',
            'code' => 200,
            'data' => $businessInfo
        ]);
    }

    public function deleteBusinessSuccess()
    {
        return response()->json([
            'success'=> true,
            'message'=> 'Business deleted successfully',
            'code'=> 201,
        ]);
    }
}
