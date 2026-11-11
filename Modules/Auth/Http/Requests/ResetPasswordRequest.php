<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends CheckResetCodeRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'password' => ['required', 'string', 'confirmed', 'max:255', Password::min(8)->letters()->numbers()],
        ];
    }
}
