<?php

use App\Http\Controllers\Api\V1\Admin\AuthController;
use App\Http\Controllers\Api\V1\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

// path1

// Route::post('/admin/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/v1/user', function (Request $request) {
    return '$request->user()';
});

Route::middleware('auth:sanctum', 'verified')
    // ->prefix('/v1')
    ->group(function () {
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

        Route::controller(StudentController::class)
             ->prefix('students')
             ->group(function () {
                 Route::get('/', 'index');
                 Route::get('/get', 'getAll');
                 Route::post('/', 'store');
                 Route::get('/{id}', 'find');
                 Route::put('/update/{id}', 'update');
                 Route::delete('/destroy/{id}', 'destroy');
             });

    });
