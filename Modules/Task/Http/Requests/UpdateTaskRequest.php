<?php

namespace Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Task\Enums\TaskPriorityEnum;
use Modules\Task\Enums\TaskStatusEnum;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string|min:3|max:255',
            'description' => 'required|string|min:3',
            'remind_date' => 'required|string', // TODO: ADD MORE RULES
            'priority'    => ['required', 'string', new Enum(TaskPriorityEnum::class), 'max:250'],
            'status'      => ['required', 'string', new Enum(TaskStatusEnum::class), 'max:250'],
        ];
    }
}
