<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Check if user is authenticated via Sanctum
        if (! $request->user() || ! $request->user()->currentAccessToken()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated. Please log in again.',
                'error_code' => 'TOKEN_INVALID',
            ], 401);
        }

        $token = $request->user()->currentAccessToken();

        // Check if token has expired (if expiration is set)
        if ($token->expires_at && $token->expires_at->isPast()) {
            // Revoke the expired token
            $token->delete();

            return response()->json([
                'status' => 'error',
                'message' => 'Token has expired. Please log in again.',
                'error_code' => 'TOKEN_EXPIRED',
            ], 401);
        }

        // Check if user account is active
        $user = $request->user();

        if ($user->isDeleted()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account has been deleted.',
                'error_code' => 'ACCOUNT_DELETED',
            ], 401);
        }

        if ($user->isPendingDeletion()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account is scheduled for deletion.',
                'error_code' => 'ACCOUNT_PENDING_DELETION',
            ], 401);
        }

        // Update last used timestamp
        $token->forceFill([
            'last_used_at' => now(),
        ])->save();

        return $next($request);
    }
}
