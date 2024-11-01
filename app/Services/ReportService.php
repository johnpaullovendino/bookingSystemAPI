<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Validator\ReportsValidator;
use App\Repositories\ReportsRepository;

class ReportService
{
    private $validator;

    private $repository;

    public function __construct(ReportsValidator $validator, ReportsRepository $repository)
    {
        $this->validator = $validator;
        $this->repository = $repository;
    }

    public function getReports(Request $request)
    {
        $this->validator->validateDatesFormat([$request->from_date, $request->to_date]);
        $this->validator->validateDateRange($request->from_date, $request->to_date);

        return $this->repository->getReportsByDate($request->from_date, $request->to_date);
    }
}
