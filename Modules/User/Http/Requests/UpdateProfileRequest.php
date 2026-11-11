<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Milwad\LaravelValidate\Rules\ValidPhoneNumber;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->user()->id;

        return [
            'name'  => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:32', new ValidPhoneNumber(), Rule::unique('users', 'phone')->ignore($id)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => is_string($this->email) ? strtolower(trim($this->email)) : $this->email,
            'phone' => $this->phone === '' ? null : $this->phone,
        ]);
    }
}
