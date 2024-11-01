<?php

namespace App\Services;

use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;

class ServiceService
{
    private $repository;

    public function __construct(ServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllServices()
    {
        return $this->repository->getAllServicesRepo();
    }

    public function createService(Request $request)
    {
        return $this->repository->createService($request);
    }

    public function updateService(Request $request, $id)
    {
        return $this->repository->updateService($request, $id);
    }

    public function deleteService($id)
    {
        return $this->repository->deleteService($id);
    }
}
