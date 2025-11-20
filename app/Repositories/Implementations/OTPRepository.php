<?php

namespace App\Repositories\Implementations;

use App\Models\Otp;
use App\Models\User;
use App\Repositories\Contracts\OTPRepositoryInterface;
use Carbon\Carbon;

class OTPRepository implements OTPRepositoryInterface
{
    public function create(array $data): Otp
    {
        return Otp::create($data);
    }

    public function findValidOTP(User $user, string $otp, string $purpose): ?Otp
    {
        return Otp::where('user_id', $user->id)
            ->where('otp_code', $otp)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }

    public function clearExistingOTPs(User $user, string $purpose): void
    {
        Otp::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->delete();
    }

    public function markAsUsed(Otp $otp): bool
    {
        return $otp->update(['is_used' => true]);
    }

    public function getLatestOTP(User $user, string $purpose): ?Otp
    {
        return Otp::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function deleteExpiredOTPs(): int
    {
        return Otp::where('expires_at', '<', Carbon::now())->delete();
    }
}
