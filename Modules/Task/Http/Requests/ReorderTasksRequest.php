<?php

namespace Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Task\Enums\TaskStatusEnum;

class ReorderTasksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tasks'            => ['required', 'array', 'min:1', 'max:500'],
            'tasks.*.id'       => ['required', 'integer', 'distinct', Rule::exists('tasks', 'id')->where('user_id', $this->user()->id)],
            'tasks.*.status'   => ['required', Rule::enum(TaskStatusEnum::class)],
            'tasks.*.position' => ['required', 'integer', 'min:0', 'max:100000'],
        ];
    }

    public function messages(): array
    {
        return ['tasks.*.id.exists' => 'One of the tasks does not exist.'];
    }
}
