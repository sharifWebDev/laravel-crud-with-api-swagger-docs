<?php

use App\Http\Controllers\Api\V1\AccountDeletionController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OTPController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\QuizController;
use Illuminate\Support\Facades\Route;

// Public routes with API key validation
Route::middleware(['api.key'])->group(function () {
    // Authentication Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // OTP
    Route::post('/otp/send', [OTPController::class, 'send']);
    Route::post('/otp/verify', [OTPController::class, 'verify']);
    Route::post('/otp/resend', [OTPController::class, 'resend']);
});

// Protected routes - require valid Sanctum token AND API key
Route::middleware(['auth:sanctum', 'token.valid', 'api.key'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    Route::post('/profile/update-image', [ProfileController::class, 'updateProfileImage']);
    Route::get('/profile/stats', [ProfileController::class, 'stats']);

    // Account Deletion Routes
    Route::post('/account/delete-request', [AccountDeletionController::class, 'requestDeletion']);
    Route::get('/account/status', [AccountDeletionController::class, 'status']);
    Route::post('/account/cancel-deletion', [AccountDeletionController::class, 'cancelDeletion']);

    // Quiz Routes
    Route::get('/categories', [QuizController::class, 'categories']);
    Route::get('/categories/{categoryId}/quizzes', [QuizController::class, 'quizzesByCategory']);
    Route::get('/quizzes/{quizId}', [QuizController::class, 'show']);
    Route::post('/quizzes/{quizId}/submit', [QuizController::class, 'submit']);
    Route::get('/quiz-results', [QuizController::class, 'results']);
    Route::get('/quiz-results/{resultId}', [QuizController::class, 'resultDetails']);
    Route::get('/quizzes/{quizId}/leaderboard', [QuizController::class, 'leaderboard']);
    Route::get('/quizzes/search', [QuizController::class, 'search']);

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
});

// Alternative route group using the custom token validation middleware
Route::middleware(['validate.token', 'api.key'])->group(function () {
    // Routes that use custom token validation
    Route::get('/user/tokens', [AuthController::class, 'listTokens']);
    Route::delete('/user/tokens/{tokenId}', [AuthController::class, 'revokeToken']);
});

// Health check route (no authentication required)
Route::get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'timestamp' => now()->toISOString(),
    ]);
});

// Test authentication route
Route::get('/test-auth', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'Authentication test successful',
        'user' => $request->user() ? [
            'id' => $request->user()->id,
            'unique_id' => $request->user()->unique_id,
            'name' => $request->user()->full_name,
            'email' => $request->user()->email,
        ] : null,
        'authenticated' => $request->user() !== null,
        'token_valid' => $request->user() && $request->user()->currentAccessToken(),
    ]);
})->middleware(['auth:sanctum', 'token.valid']);
