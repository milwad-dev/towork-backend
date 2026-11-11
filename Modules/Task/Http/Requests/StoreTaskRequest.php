<?php

namespace Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Task\Enums\TaskPriorityEnum;
use Modules\Task\Enums\TaskStatusEnum;

class StoreTaskRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'title' => [$this->titleRequirement(), 'string', 'min:1', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:20000'],
            'status' => ['sometimes', Rule::enum(TaskStatusEnum::class)],
            'priority' => ['sometimes', 'integer', Rule::enum(TaskPriorityEnum::class)],
            'category_id' => ['sometimes', 'nullable', 'integer', Rule::exists('categories', 'id')->where('user_id', $userId)],
            'label_ids' => ['sometimes', 'array', 'max:20'],
            'label_ids.*' => ['integer', 'distinct', Rule::exists('labels', 'id')->where('user_id', $userId)],
            'due_date' => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            'due_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'remind_at' => ['sometimes', 'nullable', 'date'],
            'subtasks' => ['sometimes', 'array', 'max:50'],
            'subtasks.*' => ['string', 'min:1', 'max:255'],
        ];
    }

    protected function titleRequirement(): string
    {
        return 'required';
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'The selected category does not exist.',
            'label_ids.*.exists' => 'One of the selected labels does not exist.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->title)) {
            $this->merge(['title' => trim($this->title)]);
        }

        // Accept "14:30:00" as well as "14:30".
        if (is_string($this->due_time) && preg_match('/^\d{2}:\d{2}:\d{2}$/', $this->due_time)) {
            $this->merge(['due_time' => substr($this->due_time, 0, 5)]);
        }
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if ($this->filled('due_time') && ! $this->filled('due_date') && $this->isMethod('POST')) {
                    $validator->errors()->add('due_date', 'A due date is required when a time is set.');
                }
            },
        ];
    }
}
