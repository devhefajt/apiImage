<?php

use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PhotoApiController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JwtController;


// Registration and login routes
// Route::post('register', [AuthController::class, 'register']);
// Route::post('login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::resource('/photos', PhotoApiController::class);
// });


// Public Routes
Route::post('/login', [JwtController::class, 'login']);
Route::post('/register', [JwtController::class, 'register']);

// Protected Routes
Route::middleware(['auth.jwt'])->group(function () {
    Route::resource('/photos', PhotoApiController::class);
});

// Route::get('photos', [PhotoApiController::class, 'index']); // Read All




