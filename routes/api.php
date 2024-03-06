<?php

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

Route::group(['prefix' => 'kyc'], function () {   
    Route::post('/pan_address', [App\Http\Controllers\IdentityController::class, 'pan_address'])->name('pan_address');
});

Route::group(['prefix' => 'payout'], function () {   
    Route::post('/activate_service', [App\Http\Controllers\EkoController::class, 'activate_service'])->name('activate_service');
});

Route::get('/bank_account_verify', [App\Http\Controllers\IdentityController::class, 'Bank_Account_Verification'])->name('bank_account_verify');