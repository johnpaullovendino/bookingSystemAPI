<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Report;
use Illuminate\Support\Collection;

class ReportsRepository
{
    /**
     * Create a new class instance.
     */
    public function getReportsByDate(
        string $from,
        string $to,
    ): Collection
    {
        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();

        $reports = Report::whereBetween('created_at', [$fromDate, $toDate])
                        ->orderBy('created_at', 'asc')
                        ->get();
                        
        return $reports;
    }

    public function getReportsByDateServiceBookID(
        string $from,
        string $to,
        int $serviceName,
        ?int $bookId,
    ): Collection
    {
        $from = new \DateTime($from);
        $to = new \DateTime($to);

        $query = Report::query();
        $query->whereBetween('created_at', [$from, $to]);

        if (!is_null($serviceName)) {
            $query->where('service_name', $serviceName);
        }

        if (!is_null($bookId)) {
            $query->where('book_id', $bookId);
        }

        $reports = $query->orderBy('created_at','desc')->get();

        return $reports;
    }
}
