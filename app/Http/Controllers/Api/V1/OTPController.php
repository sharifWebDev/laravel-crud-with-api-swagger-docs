<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Services\Contracts\OTPServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class OTPController extends Controller
{
    private OTPServiceInterface $otpService;

    public function __construct()
    {
        $this->otpService = app(OTPServiceInterface::class);
    }

    /**
     * @OA\Post(
     *     path="/api/otp/send",
     *     summary="Send OTP to user's email",
     *     description="Send a one-time password (OTP) to the user's email for verification purposes",
     *     tags={"OTP"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="purpose", type="string", enum={"verification", "reset", "change_email"}, example="verification", description="Purpose of the OTP request")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="OTP sent successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found.")
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
     *             @OA\Property(property="message", type="string", example="The email field is required.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to send OTP",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to send OTP: Error message")
     *         )
     *     )
     * )
     */
    public function send(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'purpose' => 'nullable|string|in:verification,reset,change_email',
            ]);

            $email = $request->input('email');
            $purpose = $request->input('purpose', 'verification');

            // Find user by email
            $user = User::where('email', $email)->first();

            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ], 404);
            }

            $this->otpService->sendOTP($user, $email, $purpose);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully.',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/otp/verify",
     *     summary="Verify OTP",
     *     description="Verify the one-time password (OTP) sent to user's email",
     *     tags={"OTP"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email", "otp"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="otp", type="string", minLength=6, maxLength=6, example="123456", description="6-digit OTP code"),
     *             @OA\Property(property="purpose", type="string", enum={"verification", "reset", "change_email"}, example="verification", description="Purpose for which OTP was sent")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OTP verified successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="OTP verified successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Invalid or expired OTP",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Invalid or expired OTP.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to verify OTP",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to verify OTP: Error message")
     *         )
     *     )
     * )
     */
    public function verify(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|string|size:6',
                'purpose' => 'nullable|string|in:verification,reset,change_email',
            ]);

            $email = $request->input('email');
            $otp = $request->input('otp');
            $purpose = $request->input('purpose', 'verification');

            // Find user by email
            $user = User::where('email', $email)->first();

            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ], 404);
            }

            $isVerified = $this->otpService->verifyOTP($user, $otp, $purpose);

            if (! $isVerified) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or expired OTP.',
                ], 422);
            }

            // Mark email as verified if this is for verification
            if ($purpose === 'verification' && ! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully.',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to verify OTP: ', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to verify OTP: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/otp/resend",
     *     summary="Resend OTP",
     *     description="Resend a one-time password (OTP) to the user's email",
     *     tags={"OTP"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="purpose", type="string", enum={"verification", "reset", "change_email"}, example="verification", description="Purpose of the OTP request")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OTP resent successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="OTP resent successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found.")
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
     *             @OA\Property(property="message", type="string", example="The email field is required.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=429,
     *         description="Too many requests",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Please wait before requesting a new OTP.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to resend OTP",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to resend OTP: Error message")
     *         )
     *     )
     * )
     */
    public function resend(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'purpose' => 'nullable|string|in:verification,reset,change_email',
            ]);

            $email = $request->input('email');
            $purpose = $request->input('purpose', 'verification');

            // Find user by email
            $user = User::where('email', $email)->first();

            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ], 404);
            }

            $this->otpService->resendOTP($user, $email, $purpose);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP resent successfully.',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to resend OTP: ', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend OTP: '.$e->getMessage(),
            ], 500);
        }
    }
}
