<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\Contracts\AccountDeletionServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountDeletionController extends Controller
{
    public function __construct(private AccountDeletionServiceInterface $accountDeletionService) {}

    /**
     * @OA\Post(
     *     path="/api/account/request-deletion",
     *     tags={"Account Deletion"},
     *     summary="Request account deletion (starts 7-day countdown)",
     *     description="User requests to delete account. It will be deleted permanently after 7 days.",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Account deletion countdown started",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Account delete request successful. Account will be permanently deleted after 7 days."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="acc_status", type="string", example="pending_deletion"),
     *                 @OA\Property(property="delete_scheduled_at", type="string", example="2025-01-15T12:00:00Z"),
     *                 @OA\Property(property="profile", ref="#/components/schemas/UserProfile")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Deletion already requested or account already deleted",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Account deletion is already pending.")
     *         )
     *     )
     * )
     */
    public function requestDeletion(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if ($user->isPendingDeletion()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Account deletion is already pending.',
                ], 400);
            }

            if ($user->isDeleted()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Account is already deleted.',
                ], 400);
            }

            $this->accountDeletionService->requestDeletion($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Account delete request successful. Account will be permanently deleted after 7 days.',
                'data' => [
                    'acc_status' => 'pending_deletion',
                    'delete_scheduled_at' => $user->delete_requested_at->addDays(7)->toISOString(),
                    'profile' => $this->formatUserProfile($user),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to request account deletion: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/account/deletion-status",
     *     tags={"Account Deletion"},
     *     summary="Get current account deletion status",
     *     description="Returns deletion countdown, scheduled deletion date, and profile info.",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully fetched account status",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successfully get the account status"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="acc_status", type="string", example="pending_deletion"),
     *                 @OA\Property(property="delete_requested_at", type="string", example="2025-01-10T12:00:00Z"),
     *                 @OA\Property(property="delete_scheduled_at", type="string", example="2025-01-17T12:00:00Z"),
     *                 @OA\Property(property="days_remaining", type="integer", example=3),
     *                 @OA\Property(property="profile", ref="#/components/schemas/UserProfile")
     *             )
     *         )
     *     )
     * )
     */
    public function status(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $deleteScheduledAt = $user->delete_requested_at ? $user->delete_requested_at->addDays(7) : null;
            $daysRemaining = $deleteScheduledAt ? now()->diffInDays($deleteScheduledAt, false) : null;

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully get the account status',
                'data' => [
                    'acc_status' => $user->acc_status,
                    'delete_requested_at' => $user->delete_requested_at?->toISOString(),
                    'delete_scheduled_at' => $deleteScheduledAt?->toISOString(),
                    'days_remaining' => $daysRemaining > 0 ? $daysRemaining : 0,
                    'profile' => $this->formatUserProfile($user),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get account status: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/account/cancel-deletion",
     *     tags={"Account Deletion"},
     *     summary="Cancel pending deletion request",
     *     description="Cancels the 7-day scheduled deletion process.",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully canceled account deletion"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No pending deletion request found"
     *     )
     * )
     */
    public function cancelDeletion(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (! $user->isPendingDeletion()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No pending deletion request found.',
                ], 400);
            }

            $this->accountDeletionService->cancelDeletion($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully canceled account deletion request.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to cancel deletion request: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Schema(
     *     schema="UserProfile",
     *     type="object",
     *
     *     @OA\Property(property="user_id", type="string", example="USR123456"),
     *     @OA\Property(property="full_name", type="string", example="Sharif Uddin"),
     *     @OA\Property(property="email", type="string", example="example@gmail.com"),
     *     @OA\Property(property="phone", type="object",
     *         @OA\Property(property="country_code", type="string", example="+880"),
     *         @OA\Property(property="number", type="string", example="1850000000")
     *     ),
     *     @OA\Property(property="profile_img", type="string", example="https://domain.com/uploads/profile.png"),
     *     @OA\Property(property="auth_type", type="string", example="email"),
     *     @OA\Property(property="app_version", type="string", example="1.0.5"),
     *     @OA\Property(property="acc_status", type="string", example="active"),
     *     @OA\Property(property="created_at", type="string", example="2025-01-01T12:00:00Z")
     * )
     */
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
            'profile_img' => $user->profile_img,
            'auth_type' => $user->auth_type,
            'app_version' => $user->app_version,
            'acc_status' => $user->acc_status,
            'created_at' => $user->created_at->toISOString(),
        ];
    }
}
