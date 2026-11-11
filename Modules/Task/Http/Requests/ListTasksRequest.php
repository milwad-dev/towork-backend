<?php

namespace Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListTasksRequest extends FormRequest
{
    public const SORTS = ['position', 'created_at', 'due_date', 'priority', 'title'];

    public const DUE_FILTERS = ['today', 'overdue', 'upcoming', 'week', 'scheduled', 'none'];

    public function rules(): array
    {
        return [
            'search'      => ['nullable', 'string', 'max:255'],
            'status'      => ['nullable', 'string', 'regex:/^(todo|in_progress|done)(,(todo|in_progress|done))*$/'],
            'priority'    => ['nullable', 'string', 'regex:/^[1-4](,[1-4])*$/'],
            'category_id' => ['nullable', 'regex:/^(none|\d+)$/'],
            'label_id'    => ['nullable', 'integer'],
            'due'         => ['nullable', Rule::in(self::DUE_FILTERS)],
            'date_from'   => ['nullable', 'date_format:Y-m-d'],
            'date_to'     => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
            'sort'        => ['nullable', Rule::in(self::SORTS)],
            'direction'   => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page'    => ['nullable', 'integer', 'min:1', 'max:200'],
            'page'        => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.regex'   => 'Status must be a comma separated list of todo, in_progress, done.',
            'priority.regex' => 'Priority must be a comma separated list of 1-4.',
        ];
    }
}
