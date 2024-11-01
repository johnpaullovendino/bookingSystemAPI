<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Responses\AuthResponse;
use App\Validator\RequestValidator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // private $validator, $service, $response;

    // public function __construct(RequestValidator $validator, AuthService $service, AuthResponse $response)
    // {
    //     $this->validator = $validator;
    //     $this->service = $service;
    //     $this->response = $response;
    // }

    // public function register(Request $request)
    // {
    //     $this->validator->validateRegister($request);
    //     $userData = $this->service->registerUser($request);

    //     return $this->response->registerSuccess($userData);
    // }

    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:6'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first(),
    //             'code' => 422,
    //             'data' => [],
    //         ]);
    //     }

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     $token = $user->createToken('auth-token')->plainTextToken;

    //     return response()->json([
    //         'succeed' => true,
    //         'message' => 'Login Successfull',
    //         'token' => $token,
    //         'code' => 200
    //     ]);
    // }
}
