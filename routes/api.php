<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\ProfileControllerAvatar;
use App\Http\Controllers\Settings\ProfileControllerBackground;

// Public Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);

    // Additional Auth Utility Routes
    Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [ApiAuthController::class, 'resetPassword']);
    Route::post('/refresh-token', [ApiAuthController::class, 'refreshToken']);
    Route::post('/verify-email', [ApiAuthController::class, 'verifyEmail']);
    Route::post('/verify-token', [ApiAuthController::class, 'verifyToken']);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);

    // Profile Info
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::post('/profile/settings', [ProfileController::class, 'profileUpdate']);

    // Avatar Upload
    Route::post('/profile/update-avatar', [ProfileControllerAvatar::class, 'update']);

    // Background Upload
    Route::post('/update-background-image', [ProfileControllerBackground::class, 'update']);
});