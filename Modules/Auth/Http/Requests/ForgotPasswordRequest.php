<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Milwad\LaravelValidate\Rules\ValidPhoneNumber;
use Milwad\LaravelValidate\Rules\ValidStrongPassword;
use Modules\Auth\Services\ResetPasswordService;

/**
 * @property $email
 */
class ForgotPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() === false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'min:3', 'max:250', 'exists:users,email'],
        ];
    }

    /**
     * Prepand for validation.
     *
     * @return void
     * @throws \Exception
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'code' => resolve(ResetPasswordService::class)->generateCode()
        ]);
    }
}
