<?php

namespace App\Services\Contracts;

use App\Models\User;

interface OTPServiceInterface
{
    /**
     * Send OTP to user's email
     */
    public function sendOTP(User $user, string $email, string $purpose = 'verification'): void;

    /**
     * Verify OTP
     */
    public function verifyOTP(User $user, string $otp, string $purpose = 'verification'): bool;

    /**
     * Resend OTP
     */
    public function resendOTP(User $user, string $email, string $purpose = 'verification'): void;

    /**
     * Generate OTP code
     */
    public function generateOTP(): string;

    /**
     * Check if OTP is valid
     */
    public function isOTPValid(User $user, string $otp, string $purpose = 'verification'): bool;

    /**
     * Clear expired OTPs
     */
    public function clearExpiredOTPs(): int;
}
