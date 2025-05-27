<?php

use Illuminate\Support\Facades\Route;
use Upcodersir\OtpAuth\Http\Controllers\OtpAuthController;

Route::post('/otp/request', [OtpAuthController::class, 'requestOtp'])->name('requestOtp')->middleware(['web']);
Route::post('/otp/verify', [OtpAuthController::class, 'verifyOtp'])->name('verifyOtp')->middleware(['web']);
