<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind AuthServiceInterface to AuthService implementation
        $this->app->bind(
            \App\Services\Contracts\AuthServiceInterface::class,
            \App\Services\Implementations\AuthService::class
        );

        // Bind TokenServiceInterface to TokenService implementation
        $this->app->bind(
            \App\Services\Contracts\TokenServiceInterface::class,
            \App\Services\Implementations\TokenService::class
        );

        // Bind other repository interfaces
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

        // OTP Bindings
        $this->app->bind(
            \App\Services\Contracts\OTPServiceInterface::class,
            \App\Services\Implementations\OTPService::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\QuizRepositoryInterface::class,
            \App\Repositories\Implementations\QuizRepository::class
        );

        // profile
        $this->app->bind(
            \App\Services\Contracts\ProfileServiceInterface::class,
            \App\Services\Implementations\ProfileService::class
        );

        // AccountDeletionServiceInterface
        $this->app->bind(
            \App\Services\Contracts\AccountDeletionServiceInterface::class,
            \App\Services\Implementations\AccountDeletionService::class
        );

        // quiz
        $this->app->bind(
            \App\Services\Contracts\QuizServiceInterface::class,
            \App\Services\Implementations\QuizService::class
        );

        // question
        $this->app->bind(
            \App\Services\Contracts\QuestionServiceInterface::class,
            \App\Services\Implementations\QuestionService::class
        );

        // answer
        $this->app->bind(
            \App\Services\Contracts\AnswerServiceInterface::class,
            \App\Services\Implementations\AnswerService::class
        );

        // result
        $this->app->bind(
            \App\Services\Contracts\ResultServiceInterface::class,
            \App\Services\Implementations\ResultService::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
