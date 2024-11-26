<?php

use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PhotoApiController;
// use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JwtController;


// Public Routes
Route::post('/login', [JwtController::class, 'login']);

// Protected Routes
Route::middleware(['auth.jwt'])->group(function () {
    Route::get('/photos', [PhotoApiController::class, 'index'])->name('photos.index');
});

// Route::get("/create-token", [JwtController::class, "createJwtToken"]);
// Route::get("/valid-token", [JwtController::class, "validToken"]);

// Route::get('/testo', function() {
//     dd(env("JWT_PRIVATE_KEY"));
// });

// Registration and login routes
// Route::post('register', [AuthController::class, 'register']);
// Route::post('login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::resource('/photos', PhotoApiController::class);
// });








