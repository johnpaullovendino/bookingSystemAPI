<?php

namespace App\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Libraries\LaravelResponse;

class AuthResponse
{
    // private $lib;

    // public function __construct(LaravelResponse $lib)
    // {
    //     $this->lib = $lib;
    // }

    // public function registerSuccess($user): JsonResponse
    // {
    //     $userData = [
    //         'user_id' => $user->id,
    //         'name' => $user->name,
    //         'email' => $user->email,
    //         'created_at' => $user->created_at,
    //     ];

    //     return $this->lib->json([
    //         'success' => true,
    //         'message' => 'User Created Successfully',
    //         'code' => 200,
    //         'data' => $userData
    //     ]);
    // }

    // public function error(string $code, string $message = ''): JsonResponse
    // {
    //     return $this->lib->json([
    //         'error_code' => $code,
    //         'error_message' => $message
    //     ]);
    // }
}
