<?php

namespace App\Responses;

use App\Libraries\LaravelResponse;

class ServiceResponse
{
    private $lib;

    public function __construct(LaravelResponse $lib)
    {
        $this->lib = $lib;
    }

    public function AllServices($services)
    {
        foreach ($services as $service) {
            $serviceInfo[] = [
                'business_id' => $service->business_id,
                'name' => $service->name,
                'price' => $service->price,
            ];
        }

        return $this->lib->json([
            'success' => true,
            'message' => 'Service Retrieved Successfully',
            'code' => 200,
            'total_services' => count($services),
            'data' => $serviceInfo ?? []
        ]);
    }

    public function createServiceSuccess($service)
    {
        $serviceInfo = [
            'business_id' => $service->business_id,
            'name' => $service->name,
            'description' => $service->description,
            'price' => $service->price,
        ];

        return $this->lib->json([
            'success' => true,
            'message' => 'Service Created Successfully',
            'code' => 200,
            'data' => $serviceInfo
        ]);
    }

    public function showServiceSuccess($service)
    {
        $serviceInfo = [
            'business_id' => $service->business_id,
            'name' => $service->name,
            'description' => $service->description,
            'price' => $service->price,
        ];

        return $this->lib->json([
            'success' => true,
            'message' => 'Service Retrieved Successfully',
            'code' => 200,
            'data' => $serviceInfo
        ]);
    }

    public function updateServiceSuccess($service)
    {
        $updatedInfo = [
            'business_id' => $service->business_id,
            'name' => $service->name,
            'description' => $service->description,
            'price' => $service->price,
        ];

        return $this->lib->json([
            'success' => true,
            'message' => 'Service Updated Successfully',
            'code' => 200,
            'data' => $updatedInfo
        ]);
    }

    public function deleteServiceSuccess()
    {
        return $this->lib->json([
            'success' => true,
            'message' => 'Service Deleted Successfully',
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
