<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/admin';

    public const ADMIN = '/admin/dashboard';

    public function boot(): void
    {
        // API Rate limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Register all route groups
        $this->routes(function () {
            // Web routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Admin routes
            Route::middleware(['web', 'auth:web', 'verified'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            // API routes
            Route::middleware(['api', 'auth:sanctum'])
                ->prefix('api/v1')
                ->name('api.')
                ->group(base_path('routes/api.php'));

            // Console/command routes if any (optional)
            // Route::middleware('console')->group(base_path('routes/console.php'));
        });
    }
}
