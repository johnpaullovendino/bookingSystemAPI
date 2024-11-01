<?php

namespace App\Validator;

use App\Exceptions\InvalidDateRangeException;
use App\Exceptions\InvalidDateFormatException;

class ReportsValidator
{

    public function validateDatesFormat(array $dates)
    {
        foreach ($dates as $date) {
            if (strtotime($date) === false)
                throw new InvalidDateFormatException;
        }
    }

    public function validateDateRange(string $from, string $to)
    {
        if (strtotime($from) > strtotime($to))
            throw new InvalidDateRangeException;
    }
}
