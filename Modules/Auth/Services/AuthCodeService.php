<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Enums\AuthCodePurpose;
use Modules\Auth\Mail\AuthCodeMail;
use Modules\Auth\Models\AuthCode;

/**
 * Issues and checks the 6-digit codes used for password resets and
 * e-mail verification. Codes are stored hashed, expire after
 * {@see self::TTL_MINUTES} minutes and allow {@see self::MAX_ATTEMPTS} wrong guesses.
 */
class AuthCodeService
{
    public const TTL_MINUTES = 60;

    public const MAX_ATTEMPTS = 5;

    /**
     * Create a new code (replacing older ones) and e-mail it.
     */
    public function send(string $email, AuthCodePurpose $purpose): void
    {
        $code = $this->issue($email, $purpose);

        Mail::to($email)->queue(new AuthCodeMail($code, $purpose));
    }

    public function issue(string $email, AuthCodePurpose $purpose): string
    {
        $email = strtolower($email);
        $code = (string) random_int(100000, 999999);

        AuthCode::query()->where(['email' => $email, 'purpose' => $purpose])->delete();
        AuthCode::query()->create([
            'email'      => $email,
            'purpose'    => $purpose,
            'code'       => Hash::make($code),
            'expires_at' => now()->addMinutes(self::TTL_MINUTES),
        ]);

        return $code;
    }

    /**
     * Check a code. When $consume is true a valid code is deleted.
     */
    public function verify(string $email, AuthCodePurpose $purpose, string $code, bool $consume = true): bool
    {
        $record = AuthCode::query()
            ->where(['email' => strtolower($email), 'purpose' => $purpose])
            ->latest('id')
            ->first();

        if (!$record || $record->isExpired() || $record->attempts >= self::MAX_ATTEMPTS) {
            return false;
        }

        if (!Hash::check($code, $record->code)) {
            $record->increment('attempts');

            return false;
        }

        if ($consume) {
            $record->delete();
        }

        return true;
    }

    public function prune(): int
    {
        return AuthCode::query()
            ->where('expires_at', '<', now())
            ->orWhere('attempts', '>=', self::MAX_ATTEMPTS)
            ->delete();
    }
}
