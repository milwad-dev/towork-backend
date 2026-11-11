<?php

namespace Modules\Task\Http\Requests;

/**
 * Partial update: every field is optional; `label_ids` replaces the whole set.
 */
class UpdateTaskRequest extends StoreTaskRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules['subtasks'], $rules['subtasks.*']);

        return $rules;
    }

    protected function titleRequirement(): string
    {
        return 'sometimes';
    }
}
