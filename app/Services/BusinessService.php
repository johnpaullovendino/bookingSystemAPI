<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\BusinessRepository;

class BusinessService
{
    private $repo;

    public function __construct(BusinessRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAllBusiness()
    {
        return $this->repo->getAllBusiness();
    }

    public function createBusiness(Request $request)
    {
        return $this->repo->createBusiness($request);
    }

    public function getBusiness($id)
    {
        return $this->repo->getBusinessById($id);
    }

    public function updateBusiness(Request $request, $id)
    {
        return $this->repo->updateBusiness($request, $id);
    }

    public function deleteBusiness($id)
    {
        return $this->repo->deleteBusiness($id);
    }
}
