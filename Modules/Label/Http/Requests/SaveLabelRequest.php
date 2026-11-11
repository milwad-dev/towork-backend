<?php

namespace Modules\Label\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Common\Enums\ColorEnum;

class SaveLabelRequest extends FormRequest
{
    public function rules(): array
    {
        $required = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'name' => [
                $required, 'string', 'min:1', 'max:100',
                Rule::unique('labels', 'name')
                    ->where('user_id', $this->user()->id)
                    ->ignore($this->route('label')),
            ],
            'color' => ['sometimes', 'string', Rule::enum(ColorEnum::class)],
        ];
    }

    public function messages(): array
    {
        return ['name.unique' => 'You already have a label with this name.'];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->name)) {
            $this->merge(['name' => trim($this->name)]);
        }
    }
}
