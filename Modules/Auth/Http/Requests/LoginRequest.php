<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:255'], // e-mail or phone
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return ['login' => 'e-mail or phone'];
    }
}
