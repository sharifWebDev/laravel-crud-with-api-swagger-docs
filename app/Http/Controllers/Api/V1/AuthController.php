<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\TokenServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    public function __construct(
        private AuthServiceInterface $authService,
        private TokenServiceInterface $tokenService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     description="Creates a new user account and returns authentication token with profile data",
     *     tags={"Authentication"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"full_name", "email", "password", "auth_type", "firebase_id"},
     *
     *             @OA\Property(property="full_name", type="string", maxLength=255, example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8, example="password123"),
     *             @OA\Property(property="auth_type", type="string", enum={"gmail", "apple", "email_pass"}, example="email_pass"),
     *             @OA\Property(property="firebase_id", type="string", example="abcd343kejfrn"),
     *             @OA\Property(property="phone_country_code", type="string", maxLength=5, example="+1"),
     *             @OA\Property(property="phone_number", type="string", maxLength=15, example="1234567890"),
     *             @OA\Property(property="profile_img", type="string", nullable=true, description="Base64 encoded image or URL", example="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQ..."),
     *             @OA\Property(property="app_version", type="string", maxLength=20, example="1.0.0"),
     *             @OA\Property(property="ip_address", type="string", format="ipv4", example="192.168.1.1")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Account created successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="1|abcdef1234567890"),
     *                 @OA\Property(property="user_id", type="string", example="60839236"),
     *                 @OA\Property(property="profile", type="object",
     *                     @OA\Property(property="user_id", type="string", example="60839236"),
     *                     @OA\Property(property="full_name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *                     @OA\Property(property="phone", type="object", nullable=true,
     *                         @OA\Property(property="country_code", type="string", example="+1"),
     *                         @OA\Property(property="number", type="string", example="1234567890")
     *                     ),
     *                     @OA\Property(property="profile_img", type="string", nullable=true),
     *                     @OA\Property(property="auth_type", type="string", example="email_pass"),
     *                     @OA\Property(property="app_version", type="string", nullable=true, example="1.0.0"),
     *                     @OA\Property(property="acc_status", type="string", example="active"),
     *                     @OA\Property(property="created_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array",
     *
     *                     @OA\Items(type="string", example="The email has already been taken.")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Registration failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Registration failed: Error message")
     *         )
     *     )
     * )
     */
    public function register(SignUpRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->register($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Account created successfully.',
                'data' => [
                    'token' => $result['token'],
                    'user_id' => $result['user']->unique_id,
                    'profile' => $this->authService->formatUserProfile($result['user']),
                ],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed: '.$e->getMessage(),
            ], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     description="Authenticate user with email and password, return access token",
     *     tags={"Authentication"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Sign in successful."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="2|ghijkl1234567890"),
     *                 @OA\Property(property="user_id", type="string", example="60839236"),
     *                 @OA\Property(property="profile", type="object",
     *                     @OA\Property(property="user_id", type="string", example="60839236"),
     *                     @OA\Property(property="full_name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *                     @OA\Property(property="phone", type="object", nullable=true),
     *                     @OA\Property(property="profile_img", type="string", nullable=true),
     *                     @OA\Property(property="auth_type", type="string", example="email_pass"),
     *                     @OA\Property(property="app_version", type="string", nullable=true),
     *                     @OA\Property(property="acc_status", type="string", example="active"),
     *                     @OA\Property(property="created_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The provided credentials are incorrect.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Login failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Login failed: Error message")
     *         )
     *     )
     * )
     */
    public function login(SignInRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Sign in successful.',
                'data' => [
                    'token' => $result['token'],
                    'user_id' => $result['user']->unique_id,
                    'profile' => $this->authService->formatUserProfile($result['user']),
                ],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/social",
     *     summary="Social authentication",
     *     description="Authenticate user using social providers (Google/Apple)",
     *     tags={"Authentication"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"full_name", "email", "auth_type", "firebase_id"},
     *
     *             @OA\Property(property="full_name", type="string", maxLength=255, example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="auth_type", type="string", enum={"gmail", "apple"}, example="gmail"),
     *             @OA\Property(property="firebase_id", type="string", example="firebase_123456"),
     *             @OA\Property(property="profile_img", type="string", nullable=true, description="Profile image URL", example="https://example.com/profile.jpg"),
     *             @OA\Property(property="app_version", type="string", maxLength=20, example="1.0.0")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Social authentication successful",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Sign in successful."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="3|mnopqr1234567890"),
     *                 @OA\Property(property="user_id", type="string", example="60839236"),
     *                 @OA\Property(property="profile", type="object"),
     *                 @OA\Property(property="is_new_user", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="New user created via social authentication",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Account created successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string"),
     *                 @OA\Property(property="user_id", type="string"),
     *                 @OA\Property(property="profile", type="object"),
     *                 @OA\Property(property="is_new_user", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Social authentication failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Social authentication failed: Error message")
     *         )
     *     )
     * )
     */
    public function socialAuth(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email',
                'auth_type' => 'required|in:gmail,apple',
                'firebase_id' => 'required|string',
                'profile_img' => 'nullable|string',
                'app_version' => 'nullable|string|max:20',
            ]);

            $result = $this->authService->handleSocialAuth($request->all());

            $message = $result['is_new_user']
                ? 'Account created successfully.'
                : 'Sign in successful.';

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => [
                    'token' => $result['token'],
                    'user_id' => $result['user']->unique_id,
                    'profile' => $this->authService->formatUserProfile($result['user']),
                    'is_new_user' => $result['is_new_user'],
                ],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Social authentication failed: '.$e->getMessage(),
            ], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout user",
     *     description="Revoke current access token and logout user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successfully logged out.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Logout failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to logout: Error message")
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Revoke current access token
            $user->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout-all",
     *     summary="Logout from all devices",
     *     description="Revoke all access tokens for the user from all devices",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logout from all devices successful",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successfully logged out from all devices.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Logout failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to logout from all devices: Error message")
     *         )
     *     )
     * )
     */
    public function logoutAll(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Delete all user's tokens
            $user->tokens()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out from all devices.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout from all devices: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/user/tokens",
     *     summary="List user's tokens",
     *     description="Get list of all access tokens for the authenticated user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Tokens retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Tokens retrieved successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="tokens", type="array",
     *
     *                     @OA\Items(type="object",
     *
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="android_auth_token"),
     *                         @OA\Property(property="abilities", type="array", @OA\Items(type="string", example="*")),
     *                         @OA\Property(property="last_used_at", type="string", format="date-time", nullable=true),
     *                         @OA\Property(property="expires_at", type="string", format="date-time", nullable=true),
     *                         @OA\Property(property="created_at", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve tokens",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve tokens: Error message")
     *         )
     *     )
     * )
     */
    public function listTokens(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $tokens = $user->tokens()->get()->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'abilities' => $token->abilities,
                    'last_used_at' => $token->last_used_at?->toISOString(),
                    'expires_at' => $token->expires_at?->toISOString(),
                    'created_at' => $token->created_at->toISOString(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Tokens retrieved successfully.',
                'data' => [
                    'tokens' => $tokens,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve tokens: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/user/tokens/{tokenId}",
     *     summary="Revoke specific token",
     *     description="Revoke a specific access token by ID",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="tokenId",
     *         in="path",
     *         required=true,
     *         description="Token ID to revoke",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Token revoked successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Token revoked successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Token not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Token not found or already revoked.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to revoke token",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to revoke token: Error message")
     *         )
     *     )
     * )
     */
    public function revokeToken(Request $request, $tokenId): JsonResponse
    {
        try {
            $user = $request->user();
            $success = $this->tokenService->revokeToken($user, $tokenId);

            if (! $success) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token not found or already revoked.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Token revoked successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to revoke token: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/auth/check-token",
     *     summary="Check token validity",
     *     description="Verify if the current access token is valid and get token details",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Token is valid",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Token is valid."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="android_auth_token"),
     *                     @OA\Property(property="abilities", type="array", @OA\Items(type="string")),
     *                     @OA\Property(property="last_used_at", type="string", format="date-time", nullable=true),
     *                     @OA\Property(property="expires_at", type="string", format="date-time", nullable=true),
     *                     @OA\Property(property="created_at", type="string", format="date-time")
     *                 ),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="string", example="60839236"),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *                     @OA\Property(property="acc_status", type="string", example="active")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Token validation failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Token validation failed: Error message")
     *         )
     *     )
     * )
     */
    public function checkToken(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $token = $user->currentAccessToken();

            return response()->json([
                'status' => 'success',
                'message' => 'Token is valid.',
                'data' => [
                    'token' => [
                        'id' => $token->id,
                        'name' => $token->name,
                        'abilities' => $token->abilities,
                        'last_used_at' => $token->last_used_at?->toISOString(),
                        'expires_at' => $token->expires_at?->toISOString(),
                        'created_at' => $token->created_at->toISOString(),
                    ],
                    'user' => [
                        'id' => $user->unique_id,
                        'name' => $user->full_name,
                        'email' => $user->email,
                        'acc_status' => $user->acc_status,
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token validation failed: '.$e->getMessage(),
            ], 401);
        }
    }

    private function formatUserProfile($user): array
    {
        return [
            'user_id' => $user->unique_id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone_country_code ? [
                'country_code' => $user->phone_country_code,
                'number' => $user->phone_number,
            ] : null,
            'profile_img' => $user->profile_img ? url($user->profile_img) : null,
            'auth_type' => $user->auth_type,
            'app_version' => $user->app_version,
            'acc_status' => $user->acc_status,
            'created_at' => $user->created_at->toISOString(),
        ];
    }
}
