<?php

namespace App\Http\Controllers\Admin;

use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Responses\BusinessResponse;
use App\Services\BusinessService;
use App\Validator\RequestValidator;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
    private $validator, $service, $response;

    public function __construct(RequestValidator $validator, BusinessService $service, BusinessResponse $response)
    {
        $this->validator = $validator;
        $this->service = $service;
        $this->response = $response;
    }

    public function index()
    {   
        $businesses = $this->service->getAllBusiness();
        
        return $this->response->getAllBusinesses($businesses);
    }
    
    public function create(Request $request)
    {
        $this->validator->validateBusiness($request);

        $business = $this->service->createBusiness($request);

        return $this->response->createBusinessSuccess($business);
    }

    public function show($id)
    {
        $business = $this->service->getBusiness($id);

        return $this->response->getBusinessSuccess($business);
    }

    public function update(Request $request, $id)
    {
        $this->validator->validateBusiness($request);

        $business = $this->service->updateBusiness($request,  $id);

        return $this->response->updateBusinessSuccess(($business));
    }

    public function delete($id) 
    {
        $this->service->deleteBusiness($id);

        return $this->response->deleteBusinessSuccess();
    }
}
