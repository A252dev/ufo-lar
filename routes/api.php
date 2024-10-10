<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name("auth-register");
    Route::post('/login', [AuthController::class, 'login'])->name("auth-login");
    Route::post('/logout', [AuthController::class, 'logout'])->name("auth-logout");
    Route::post('/profile', [AuthController::class, 'profile'])->name("auth-profile");
});

