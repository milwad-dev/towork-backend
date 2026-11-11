<?php

namespace Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSubtaskRequest extends FormRequest
{
    public function rules(): array
    {
        $required = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'title'        => [$required, 'string', 'min:1', 'max:255'],
            'is_completed' => ['sometimes', 'boolean'],
            'position'     => ['sometimes', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->title)) {
            $this->merge(['title' => trim($this->title)]);
        }
    }
}
