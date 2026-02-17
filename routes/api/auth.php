<?php

use App\Http\Controllers\Api\AuthController;

Route::middleware('throttle:10,1')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('me', [AuthController::class, 'me'])->name('auth.me');
    Route::put('change-password', [AuthController::class, 'changePassword'])->name('auth.change-password');
});
