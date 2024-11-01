<?php

namespace Modules\Task\Services;

use Modules\Task\Models\Task;

class TaskService
{
    /**
     * Create task and return.
     */
    public function store(array $data): Task
    {
        return Task::query()->create([
            'user_id'     => auth()->id(),
            'title'       => $data['title'],
            'description' => $data['description'],
            'remind_date' => $data['remind_date'],
            'priority'    => $data['priority'],
            'status'      => $data['status'],
        ]);
    }

    /**
     * Update task and return bool.
     */
    public function update(array $data, Task $task): bool
    {
        return $task->update([
            'title'       => $data['title'],
            'description' => $data['description'],
            'remind_date' => $data['remind_date'],
            'priority'    => $data['priority'],
            'status'      => $data['status'],
        ]);
    }
}
