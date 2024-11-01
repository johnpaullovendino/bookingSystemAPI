<?php

namespace App\Exceptions;

class InvalidDateRangeException extends \Exception
{
    public function render($request)
    {
        return response()->json([
            'response_code' => '402',
            'message' => 'Invalid date range.'
        ], 402);
    }
}


