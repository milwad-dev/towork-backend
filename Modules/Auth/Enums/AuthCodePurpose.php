<?php

namespace Modules\Auth\Enums;

enum AuthCodePurpose: string
{
    case ResetPassword = 'reset_password';
    case VerifyEmail = 'verify_email';

    public function subject(): string
    {
        return match ($this) {
            self::ResetPassword => 'Your password reset code',
            self::VerifyEmail => 'Verify your e-mail address',
        };
    }
}
