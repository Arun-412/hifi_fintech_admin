<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ChairController;

Route::get('/verify', function () { return view('auth.OTP'); });

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () { return view('auth.Login'); })->name('login');
    // Route::get('/signup', function () { return view('auth.Register'); });
    Route::post('/loggedin', [UserController::class, 'Login'])->name('loggedin');
    // Route::post('/register', [UserController::class, 'Register'])->name('Register');
    Route::post('/signin', [UserController::class, 'Signin'])->name('Signin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/distributer', function () { return view('user_management.distributer'); })->name('distributer');
    Route::get('/report', function () { return view('report'); })->name('report');
    Route::get('/logout', [UserController::class, 'Logout'])->name('logout');

    Route::get('/profile', function () { return view('profile'); })->name('profile');
    Route::get('/settings', function () { return view('settings'); })->name('settings');
    Route::get('/support', function () { return view('support'); })->name('support');

    Route::get('/services', [ServicesController::class, 'services'])->name('services');

    Route::group(['prefix' => 'services'], function () {  
        Route::group(['prefix' => 'payout'], function () {  
            Route::group(['prefix' => 'eko'], function () {  
                Route::get('/', function () { return view('services.payout'); })->name('payout_eko');
                Route::get('/add_rule', [ChairController::class, 'add_rule'])->name('payout_eko_add_rule'); 
            }); 
        });  
    }); 

    Route::group(['prefix' => 'print'], function () {   
        Route::get('/transaction', function () { return view('print.transaction'); })->name('print_transaction');
    }); 
});

