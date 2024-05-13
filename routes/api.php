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
    Route::post('/transaction',[App\Http\Controllers\EkoController::class, 'payout_transaction'])->name('payout_transaction');
    Route::get('/transaction_enquiry',[App\Http\Controllers\EkoController::class, 'payout_transaction_enquiry'])->name('payout_transaction_enquiry');
});

Route::post('/bank_account_verify', [App\Http\Controllers\IdentityController::class, 'Bank_Account_Verification'])->name('bank_account_verify');
Route::post('/create_customer', [App\Http\Controllers\IdentityController::class, 'Create_Customer'])->name('Create_Customer');
Route::get('/Verify_Customer', [App\Http\Controllers\IdentityController::class, 'Verify_Customer'])->name('Verify_Customer');
Route::get('/Get_Customer', [App\Http\Controllers\IdentityController::class, 'Get_Customer'])->name('Get_Customer');
Route::post('/Get_Banks_List', [App\Http\Controllers\IdentityController::class, 'Get_Banks_List'])->name('Get_Banks_List');