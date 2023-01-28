<?php

namespace Modules\Auth\Http\Requests\Password;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Milwad\LaravelValidate\Rules\ValidStrongPassword;

/**
 * @property $code
 * @property $password
 */
class ResetPasswordRequest extends FormRequest
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
            'code'      => ['required', 'numeric', 'between:100000,999999', 'exists:reset_code_passwords,code'],
            'password'  => ['required', 'string', 'min:8', 'max:250', new ValidStrongPassword()]
        ];
    }

    /**
     * Prepand for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'password' => Hash::make($this->password)
        ]);
    }
}
