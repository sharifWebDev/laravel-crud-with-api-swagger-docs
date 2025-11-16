<?php

// routes/admin.php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

// path1

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'), 'verified',
])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/artisan/optimize', [AdminController::class, 'optimize'])->name('artisan.optimize');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/toggle-status/{id}', [AdminController::class, 'toggleStatus'])->name('data.toggleStatus');

    Route::prefix('/students')
        ->controller(StudentController::class)
        ->group(function () {
            Route::get('/', 'index')->name('students.index');
            Route::get('/create', 'create')->name('students.create');
            Route::get('/{id}/edit', 'edit')->name('students.edit');
            Route::get('/{id}/show', 'show')->name('students.show');
        });

});
