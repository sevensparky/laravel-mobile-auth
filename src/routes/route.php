<?php

use Illuminate\Support\Facades\Route;
use SevenSparky\LaravelMobileAuth\Http\Controllers\AuthController;

Route::group(['middleware' => 'guest'], function (){
    Route::get('login', [AuthController::class, 'login'])->name('laravel_mobile_auth.login');
    Route::get('otp-login', [AuthController::class, 'OTPLogin'])->name('laravel_mobile_auth.otp-login');
    Route::post('otp-login', [AuthController::class, 'OTPCheck'])->name('laravel_mobile_auth.otp-check');
    Route::get('password-login', [AuthController::class, 'passwordLogin'])->name('laravel_mobile_auth.password-login');
    Route::post('password-login/check', [AuthController::class, 'passwordCheck'])->name('laravel_mobile_auth.password.check');
    Route::post('auth', [AuthController::class, 'checkAuth'])->name('laravel_mobile_auth.auth');
});

Route::group(['middleware' => 'auth'], function (){

    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('laravel_mobile_auth.dashboard');
    Route::get('logout', [AuthController::class, 'logout'])->name('laravel_mobile_auth.logout');
});


