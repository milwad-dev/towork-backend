<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Common\Enums\ColorEnum;

class SaveCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        $required = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'name' => [
                $required, 'string', 'min:1', 'max:100',
                Rule::unique('categories', 'name')
                    ->where('user_id', $this->user()->id)
                    ->ignore($this->route('category')),
            ],
            'color' => ['sometimes', 'string', Rule::enum(ColorEnum::class)],
            'icon' => ['nullable', 'string', 'max:50', 'regex:/^[a-z0-9-]+$/'],
        ];
    }

    public function messages(): array
    {
        return ['name.unique' => 'You already have a category with this name.'];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->name)) {
            $this->merge(['name' => trim($this->name)]);
        }
    }
}
