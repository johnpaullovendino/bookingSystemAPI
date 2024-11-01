<?php

namespace App\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Libraries\LaravelResponse;
use App\Models\BookWithPromoCode;
use Illuminate\Support\Collection;

class ReportResponse
{
    private $lib;
    private $bookWithPromo;
    public function __construct(LaravelResponse $lib,  BookWithPromoCode $bookWithPromo)
    {
        $this->lib = $lib;
        $this->bookWithPromo = $bookWithPromo;
    }

    private function getPromoDetails($report)
    {   
        $promoDetails = [];

        if($this->bookWithPromo->hasPromoCode($report->promo_code) === true) {
            $promoDetails['promo_code'] = $report->promo_code;
            $promoDetails['discount'] = $report->discount;
            $promoDetails['total_amount'] = $report->total_amount;
        }

        return $promoDetails;
    }

    public function reports(
        Request $request,
        Collection $reports
    ): JsonResponse{
        foreach ($reports as $report) {
            $reportsData[] = [
                'book_id' => $report->book_id,
                'clientName' => $report->name,
                'serviceName' => $report->service_name,
                'amount' => $report->amount,
                'payment_method' => $report->payment_method,
                'payment_status' => $report->payment_status,
                'bookStatus' => $report->status,
                'note' => $report->note,
                'booked_at' => $report->created_at,
                'hasPromo' => $report->promo,
                'promoDetails' => (object) $this->getPromoDetails($report)
            ];
        }

        return $this->lib->json([
            'response_code' => '200',
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'total_reports' => $reports->count(),
            'data' => $reportsData ?? []
        ]);
    }

    // public function error(string $code, string $message = ''): JsonResponse
    // {
    //     return $this->lib->json([
    //         'error_code' => $code,
    //         'error_message' => $message
    //     ]);
    // }
}
