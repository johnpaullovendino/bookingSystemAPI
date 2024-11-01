<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Responses\ServiceResponse;
use App\Services\ServiceService;
use App\Validator\RequestValidator;


class ServiceController extends Controller
{
    private $validator, $service, $response;

    public function __construct(RequestValidator $validator, ServiceService $service, ServiceResponse $response)
    {
        $this->validator = $validator;
        $this->service = $service;
        $this->response = $response;
    }

    public function index()
    {
        $services = $this->service->getAllServices();

        return $this->response->allServices($services);
    }

    public function create(Request $request)
    {
        $this->validator->validateService($request);

        $service = $this->service->createService($request);

        return $this->response->createServiceSuccess($service);
    }

    public function update(Request $request, $id)
    {
        $this->validator->validateService($request);

        $updatedService = $this->service->updateService($request, $id);

        return $this->response->updateServiceSuccess($updatedService);
    }

    public function delete($id)
    {
        $this->service->deleteService($id);

        return $this->response->deleteServiceSuccess();
    }
}
