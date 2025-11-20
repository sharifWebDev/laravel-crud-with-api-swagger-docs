<?php

namespace App\Http\Middleware;

use App\Services\Contracts\TokenServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateToken
{
    public function __construct(private TokenServiceInterface $tokenService) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $scope = '*'): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access token is required.',
                'error_code' => 'TOKEN_MISSING',
            ], 401);
        }

        // Check if token exists and is valid
        if (! $this->tokenService->tokenExists($token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or revoked token.',
                'error_code' => 'TOKEN_INVALID',
            ], 401);
        }

        $user = $this->tokenService->getTokenUser($token);

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found for this token.',
                'error_code' => 'USER_NOT_FOUND',
            ], 401);
        }

        // Check account status
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

        // Bind user to request
        $request->merge(['user' => $user]);
        auth()->setUser($user);

        // Check token abilities if scope is specified
        if ($scope !== '*' && ! $this->hasAbility($user, $scope)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient permissions.',
                'error_code' => 'INSUFFICIENT_PERMISSIONS',
            ], 403);
        }

        return $next($request);
    }

    private function hasAbility($user, string $scope): bool
    {
        $abilities = $this->tokenService->getTokenAbilities($user);

        return in_array($scope, $abilities) || in_array('*', $abilities);
    }
}
