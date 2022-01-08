<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::get('/token',[ApiController::class,'getApi']);
// Route::post('/token',[ApiController::class,'createApi']);
// Route::put('/token/{id}',[ApiController::class,'editApi']);
// Route::delete('/token/{id}',[ApiController::class,'deleteApi']);

Route::post('/image/file',[ImageController::class,'removeFile']);
Route::get('/image/folder',[ImageController::class,'removeFolder']);
Route::post('/image/url',[ImageController::class,'removeUrl']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
