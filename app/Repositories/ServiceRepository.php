<?php

namespace App\Repositories;

use App\Models\Service;
use App\Models\Business;
use Illuminate\Http\Request;

class ServiceRepository
{
    public function getAllServicesRepo()
    {
        $business = Business::first();
        
        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'No business found',
                'code' => 404,
            ]);
        }

        $services = Service::where('business_id', $business->id)->paginate(10);
        
        return $services;
    }
    public function createService(Request $request)
    {
        $business = Business::find($request->business_id);

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Business not found',
                'code' => 404
            ], 404);
        }

        $service = new Service();
        $service->name = $request->name;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->business_id = $request->business_id;
        $service->save();

        return $service;
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->name = $request->name;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->save();

        return $service;
    }

    public function deleteService($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return $service;
    }
}
