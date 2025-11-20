<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('API_KEY');

        if (! $apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: API Key is required',
            ], 401);
        }

        // Get platform from request
        $platform = $this->getPlatformFromRequest($request);

        // Validate API key directly
        $isValid = $this->validateApiKey($apiKey, $platform);

        if (! $isValid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Invalid API Key',
            ], 401);
        }

        // Add platform to request for later use
        $request->merge(['platform' => $platform]);

        return $next($request);
    }

    private function getPlatformFromRequest(Request $request): string
    {
        $userAgent = $request->header('User-Agent', '');

        if (str_contains(strtolower($userAgent), 'android')) {
            return 'android';
        } elseif (str_contains(strtolower($userAgent), 'iphone') ||
                  str_contains(strtolower($userAgent), 'ipad')) {
            return 'ios';
        }

        return 'unknown';
    }

    private function validateApiKey(string $apiKey, string $platform): bool
    {
        return \App\Models\ApiKey::where('key', $apiKey)
            ->where('platform', $platform)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }
}
