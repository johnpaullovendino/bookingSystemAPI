<?php

namespace App\Exceptions;

class InvalidDateFormatException extends \Exception
{
    public function render($request)
    {
        return response()->json([
            'response_code' => '401',
            'message' => 'Invalid date format.'
        ], 400);
    }
}
