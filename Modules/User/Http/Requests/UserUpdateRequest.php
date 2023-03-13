<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Milwad\LaravelValidate\Rules\ValidPhoneNumber;
use Milwad\LaravelValidate\Rules\ValidStrongPassword;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'min:3', 'max:250'],
            'email'     => ['required', 'email', 'min:3', 'max:255', 'unique:users,email'],
            'phone'     => ['required', 'numeric', 'unique:users,phone', new ValidPhoneNumber()],
            'password'  => ['nullable', 'string', new ValidStrongPassword()],
        ];
    }
}
