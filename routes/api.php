<?php

use App\Http\Controllers\API\MobileUsersController;
use App\Http\Controllers\API\MobileUsersRegistrationController;
use App\Http\Controllers\API\PlantsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//user
Route::post('mobilelogin', [MobileUsersRegistrationController::class, 'login']);
Route::post('mobileregister', [MobileUsersRegistrationController::class, 'register']);
Route::post('mobilelogout', [MobileUsersRegistrationController::class, 'logout']);

//plants
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/data', [PlantsController::class, 'index']); 
    Route::post('/upload-image', [PlantsController::class, 'store']);    
});




