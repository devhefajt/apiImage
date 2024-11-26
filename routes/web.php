<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-keys', function() {
    dd(file_get_contents(config('jwt.private_key')), file_get_contents(config('jwt.public_key')));
    // dd(file_exists(storage_path('keys/private.key')));

});

Route::resource('/photos', PhotoController::class);