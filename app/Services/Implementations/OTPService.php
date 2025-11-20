<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Repositories\Contracts\OTPRepositoryInterface;
use App\Services\Contracts\OTPServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class OTPService implements OTPServiceInterface
{
    private const OTP_EXPIRY_MINUTES = 10;

    private const OTP_RESEND_DELAY_MINUTES = 1;

    public function __construct(private OTPRepositoryInterface $otpRepository) {}

    public function sendOTP(User $user, string $email, string $purpose = 'verification'): void
    {
        // Clear any existing OTPs for this purpose
        $this->otpRepository->clearExistingOTPs($user, $purpose);

        // Generate new OTP
        $otpCode = $this->generateOTP();
        $expiresAt = Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES);

        // Store OTP
        $this->otpRepository->create([
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'purpose' => $purpose,
            'expires_at' => $expiresAt,
        ]);

        // Send OTP via email (you can implement email sending logic here)
        $this->sendOTPEmail($user, $email, $otpCode, $purpose);
    }

    public function verifyOTP(User $user, string $otp, string $purpose = 'verification'): bool
    {
        $otpRecord = $this->otpRepository->findValidOTP($user, $otp, $purpose);

        if (! $otpRecord) {
            return false;
        }

        // Mark OTP as used
        $this->otpRepository->markAsUsed($otpRecord);

        return true;
    }

    public function resendOTP(User $user, string $email, string $purpose = 'verification'): void
    {
        // Check if resend is allowed (rate limiting)
        $lastOTP = $this->otpRepository->getLatestOTP($user, $purpose);

        if ($lastOTP && $lastOTP->created_at->addMinutes(self::OTP_RESEND_DELAY_MINUTES)->isFuture()) {
            throw new \Exception('Please wait before requesting a new OTP.');
        }

        $this->sendOTP($user, $email, $purpose);
    }

    public function generateOTP(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function isOTPValid(User $user, string $otp, string $purpose = 'verification'): bool
    {
        return $this->otpRepository->findValidOTP($user, $otp, $purpose) !== null;
    }

    public function clearExpiredOTPs(): int
    {
        return $this->otpRepository->deleteExpiredOTPs();
    }

    private function sendOTPEmail(User $user, string $email, string $otp, string $purpose): void
    {
        $subject = $this->getOTPSubject($purpose);
        Mail::to($email)->send(new \App\Mail\OTPMail($user, $otp, $purpose, $subject));
    }

    private function getOTPSubject(string $purpose): string
    {
        return match ($purpose) {
            'verification' => 'Verify Your Email Address',
            'reset' => 'Reset Your Password',
            'change_email' => 'Confirm Email Change',
            default => 'Your Verification Code',
        };
    }
}
