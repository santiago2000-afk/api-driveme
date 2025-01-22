<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\RidesController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('users', UsersController::class);
    Route::apiResource('drivers', DriversController::class);
    Route::apiResource('rides', RidesController::class);
    Route::patch('/drivers/{driver}/availability', [DriversController::class, 'updateAvailability']);
    Route::get('/user/profile', [UsersController::class, 'profile']);
    Route::get('/rides/{ride}/locations', [RidesController::class, 'locations']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});
