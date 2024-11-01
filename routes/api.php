<?php

use App\Http\Controllers\Admin\BusinessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\Business\ServiceController;
use App\Http\Controllers\Reports\ReportsController;


Route::get('/businesses', [BusinessController::class, 'index']);
Route::post('/createBusiness', [BusinessController::class, 'create']);
Route::get('/getBusiness/{id}', [BusinessController::class, 'show']);
Route::delete('/deleteBusiness/{id}', [BusinessController::class, 'delete']);
Route::put('/updateBusiness/{id}', [BusinessController::class, 'update']);


Route::get('/services', [ServiceController::class, 'index']);
Route::post('/createService', [ServiceController::class, 'create']);
Route::get('/getService/{id}', [ServiceController::class, 'show']);
Route::delete('/deleteService/{id}', [ServiceController::class, 'delete']);
Route::put('/updateService/{id}', [ServiceController::class, 'update']);

Route::get('/bookings', [BookingsController::class, 'index']);
Route::post('/createBooking', [BookingsController::class, 'create']);
Route::get('/getBooking/{id}', [BookingsController::class, 'show']);
Route::delete('/deleteBooking/{id}', [BookingsController::class, 'delete']);

Route::post('/report', [ReportsController::class, 'getReports']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');