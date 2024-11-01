<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

class InvalidRequestException extends \Exception
{
    public function render($request)
    {
        return response()->json([
            'response_code' => 400,
            'message' => $this->getMessage(),
        ], 400);
    }
}
