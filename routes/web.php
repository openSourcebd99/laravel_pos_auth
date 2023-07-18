<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

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

Route::post('/user-registration', [UserController::class, 'userRegistration']);
Route::post('/user-login', [UserController::class, 'userLogin']);
Route::post('/send-otp-code', [UserController::class, 'sendOTPCode']);
Route::post('/verify-otp-code', [UserController::class, 'verifyOTPCode']);
Route::post('/reset-password', [UserController::class, 'resetPassword'])
    ->middleware(TokenVerificationMiddleware::class);
