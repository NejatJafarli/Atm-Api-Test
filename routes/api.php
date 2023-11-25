<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// Register
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);

// Login
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

// Logout
Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    //post payment
    Route::post('transaction/withdrawal', [App\Http\Controllers\API\TransactionController::class, 'withdrawal']);
    //get history
    Route::get('transaction/history', [App\Http\Controllers\API\TransactionController::class, 'getHistory']);
    //delete history
    Route::delete('transaction/history/{id}', [App\Http\Controllers\API\TransactionController::class, 'deleteHistory']);
});
