<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Milwad\LaravelValidate\Rules\ValidPhoneNumber;
use Milwad\LaravelValidate\Rules\ValidStrongPassword;

/**
 * @property $password
 */
class RegisterRequest extends FormRequest
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
            'name'      => ['required', 'string', 'min:3', 'max:250', 'unique:users,name'],
            'email'     => ['required', 'email', 'min:3', 'max:250', 'unique:users,email'],
            'phone'     => ['required', 'numeric', new ValidPhoneNumber()],
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
