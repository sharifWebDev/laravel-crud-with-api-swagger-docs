<?php

namespace App\Repositories\Contracts;

use App\Models\Otp;
use App\Models\User;

interface OTPRepositoryInterface
{
    /**
     * Create a new OTP record
     */
    public function create(array $data): Otp;

    /**
     * Find valid OTP for user
     */
    public function findValidOTP(User $user, string $otp, string $purpose): ?Otp;

    /**
     * Clear existing OTPs for user and purpose
     */
    public function clearExistingOTPs(User $user, string $purpose): void;

    /**
     * Mark OTP as used
     */
    public function markAsUsed(Otp $otp): bool;

    /**
     * Get latest OTP for user and purpose
     */
    public function getLatestOTP(User $user, string $purpose): ?Otp;

    /**
     * Delete expired OTPs
     */
    public function deleteExpiredOTPs(): int;
}
