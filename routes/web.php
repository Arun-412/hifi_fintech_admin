<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/verify', function () { return view('auth.OTP'); });

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () { return view('auth.Login'); })->name('login');
    // Route::get('/signup', function () { return view('auth.Register'); });
    Route::post('/loggedin', [UserController::class, 'Login'])->name('loggedin');
    // Route::post('/register', [UserController::class, 'Register'])->name('Register');
    Route::post('/signin', [UserController::class, 'Signin'])->name('Signin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); });
    Route::get('/services', function () { return view('services.services'); });
    Route::get('/logout', [UserController::class, 'Logout'])->name('logout');
});

