<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Debug: Check if this method is being called
        \Log::info('RepositoryServiceProvider::register() called');

        // Repositories
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\Implementations\UserRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\ApiKeyRepositoryInterface::class,
            \App\Repositories\Implementations\ApiKeyRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\OTPRepositoryInterface::class,
            \App\Repositories\Implementations\OTPRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\QuizRepositoryInterface::class,
            \App\Repositories\Implementations\QuizRepository::class
        );

        // Services
        $this->app->bind(
            \App\Services\Contracts\AuthServiceInterface::class,
            \App\Services\Implementations\AuthService::class
        );

        $this->app->bind(
            \App\Services\Contracts\TokenServiceInterface::class,
            \App\Services\Implementations\TokenService::class
        );

        $this->app->bind(
            \App\Services\Contracts\OTPServiceInterface::class,
            \App\Services\Implementations\OTPService::class
        );

        $this->app->bind(
            \App\Services\Contracts\AccountDeletionServiceInterface::class,
            \App\Services\Implementations\AccountDeletionService::class
        );

        $this->app->bind(
            \App\Services\Contracts\ProfileServiceInterface::class,
            \App\Services\Implementations\ProfileService::class
        );

        $this->app->bind(
            \App\Services\Contracts\QuizServiceInterface::class,
            \App\Services\Implementations\QuizService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
