<?php

namespace App\Repositories;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessRepository
{
    public function getAllBusiness()
    {
        $business = Business::orderBy('id', 'asc')->paginate(10);

        return $business;
    }

    public function createBusiness(Request $request)
    {
        $business = Business::create([
            'name' => $request['name'],
            'status' => $request['status'],
            'opening_hours' => $request['opening_hours']
        ]);

        return $business;
    }

    public function getBusinessById($id)
    {
        $business = Business::findOrFail($id);

        return $business;
    }

    public function updateBusiness(Request $request, $id)
    {
        $updatedBusiness = Business::findOrFail($id);
        $updatedBusiness->update([
            'name'=> $request['name'],
            'user_id'=> $request['user_id'],
            'status'=> $request['status'],
            'opening_hours' => $request['opening_hours'],
        ]);

        return $updatedBusiness;
    }

    public function deleteBusiness($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();

        return $business;
    }
}
