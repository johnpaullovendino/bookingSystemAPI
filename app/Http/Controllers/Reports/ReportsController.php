<?php

namespace App\Http\Controllers\Reports;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Responses\ReportResponse;
use App\Validator\RequestValidator;
use App\Http\Controllers\Controller;


class ReportsController extends Controller
{
    private $validator;
    private $service;
    private $response;

    public function __construct(RequestValidator $validator, ReportService $service, ReportResponse $response)
    {
        $this->validator = $validator;
        $this->service = $service;
        $this->response = $response;
    }

    public function getReports(Request $request)
    {
        $this->validator->validateReports($request);

        $reports = $this->service->getReports($request);

        return $this->response->reports($request, $reports);
    }
}
