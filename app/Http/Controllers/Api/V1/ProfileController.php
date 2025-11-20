<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\Contracts\ProfileServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class ProfileController extends Controller
{
    private ProfileServiceInterface $profileService;

    public function __construct()
    {
        $this->profileService = app(ProfileServiceInterface::class);
    }

    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get user profile",
     *     description="Retrieve the authenticated user's complete profile information",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successfully user data get."),
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
     *                     @OA\Property(property="profile_img", type="string", nullable=true, example="https://example.com/profile.jpg"),
     *                     @OA\Property(property="auth_type", type="string", example="email_pass"),
     *                     @OA\Property(property="app_version", type="string", nullable=true, example="1.0.0"),
     *                     @OA\Property(property="ip_address", type="string", format="ipv4", example="192.168.1.1"),
     *                     @OA\Property(property="firebase_id", type="string", example="abcd343kejfrn"),
     *                     @OA\Property(property="acc_status", type="string", example="active"),
     *                     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
     *                     @OA\Property(property="last_login_at", type="string", format="date-time"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
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
     *         description="Failed to get profile",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to get profile: Error message")
     *         )
     *     )
     * )
     */
    public function show(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $profile = $this->profileService->getUserProfile($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully user data get.',
                'data' => [
                    'token' => $request->bearerToken(),
                    'user_id' => $user->unique_id,
                    'profile' => $profile,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get profile: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/profile",
     *     summary="Update user profile",
     *     description="Update the authenticated user's profile information",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="full_name", type="string", maxLength=255, example="John Smith"),
     *             @OA\Property(property="phone_country_code", type="string", maxLength=5, example="+1"),
     *             @OA\Property(property="phone_number", type="string", maxLength=15, example="9876543210"),
     *             @OA\Property(property="profile_img", type="string", nullable=true, description="Base64 encoded image or URL", example="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQ..."),
     *             @OA\Property(property="app_version", type="string", maxLength=20, example="1.1.0"),
     *             @OA\Property(property="ip_address", type="string", format="ipv4", example="192.168.1.100")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Profile updated successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="profile", type="object",
     *                     @OA\Property(property="user_id", type="string", example="60839236"),
     *                     @OA\Property(property="full_name", type="string", example="John Smith"),
     *                     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *                     @OA\Property(property="phone", type="object", nullable=true,
     *                         @OA\Property(property="country_code", type="string", example="+1"),
     *                         @OA\Property(property="number", type="string", example="9876543210")
     *                     ),
     *                     @OA\Property(property="profile_img", type="string", nullable=true),
     *                     @OA\Property(property="auth_type", type="string", example="email_pass"),
     *                     @OA\Property(property="app_version", type="string", example="1.1.0"),
     *                     @OA\Property(property="ip_address", type="string", format="ipv4", example="192.168.1.100"),
     *                     @OA\Property(property="firebase_id", type="string", example="abcd343kejfrn"),
     *                     @OA\Property(property="acc_status", type="string", example="active"),
     *                     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
     *                     @OA\Property(property="last_login_at", type="string", format="date-time"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
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
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
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
     *         description="Failed to update profile",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to update profile: Error message")
     *         )
     *     )
     * )
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $data = $request->validated();

            $updatedUser = $this->profileService->updateProfile($user, $data);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully.',
                'data' => [
                    'profile' => $this->profileService->getUserProfile($updatedUser),
                ],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/profile/change-password",
     *     summary="Change user password",
     *     description="Change the authenticated user's password",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"current_password", "new_password", "new_password_confirmation"},
     *
     *             @OA\Property(property="current_password", type="string", format="password", example="oldpassword123"),
     *             @OA\Property(property="new_password", type="string", format="password", minLength=8, example="newpassword456"),
     *             @OA\Property(property="new_password_confirmation", type="string", format="password", example="newpassword456")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Password changed successfully.")
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
     *             @OA\Property(property="message", type="string", example="The current password is incorrect."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="current_password", type="array",
     *
     *                     @OA\Items(type="string", example="The current password is incorrect.")
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
     *         description="Failed to change password",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to change password: Error message")
     *         )
     *     )
     * )
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user = $request->user();

            $this->profileService->changePassword($user, $request->current_password, $request->new_password);

            return response()->json([
                'status' => 'success',
                'message' => 'Password changed successfully.',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to change password: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/profile/update-image",
     *     summary="Update profile image",
     *     description="Update the authenticated user's profile image (base64 or URL)",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"profile_img"},
     *
     *             @OA\Property(property="profile_img", type="string", description="Base64 encoded image or image URL", example="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQ...")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile image updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Profile image updated successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="profile_img", type="string", example="https://example.com/profiles/profile_60839236_1234567890.jpg")
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
     *             @OA\Property(property="message", type="string", example="The profile img field is required.")
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
     *         description="Failed to update profile image",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to update profile image: Error message")
     *         )
     *     )
     * )
     */
    public function updateProfileImage(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'profile_img' => 'required|string', // base64 or URL
            ]);

            $user = $request->user();
            $profileImage = $request->input('profile_img');

            $updatedUser = $this->profileService->updateProfileImage($user, $profileImage);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile image updated successfully.',
                'data' => [
                    'profile_img' => $updatedUser->profile_img,
                ],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile image: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/profile/stats",
     *     summary="Get user statistics",
     *     description="Retrieve the authenticated user's activity statistics and quiz performance",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User stats retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User stats retrieved successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="quiz_stats", type="object",
     *                     @OA\Property(property="total_quizzes_taken", type="integer", example=15),
     *                     @OA\Property(property="average_score", type="number", format="float", example=85.5),
     *                     @OA\Property(property="total_correct_answers", type="integer", example=128),
     *                     @OA\Property(property="total_time_spent", type="integer", example=3600, description="Total time spent in seconds")
     *                 ),
     *                 @OA\Property(property="account_stats", type="object",
     *                     @OA\Property(property="member_since", type="string", example="2 months ago"),
     *                     @OA\Property(property="account_status", type="string", example="active"),
     *                     @OA\Property(property="email_verified", type="boolean", example=true)
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
     *         description="Failed to get user stats",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to get user stats: Error message")
     *         )
     *     )
     * )
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $stats = $this->profileService->getUserStats($user);

            return response()->json([
                'status' => 'success',
                'message' => 'User stats retrieved successfully.',
                'data' => $stats,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get user stats: '.$e->getMessage(),
            ], 500);
        }
    }
}
