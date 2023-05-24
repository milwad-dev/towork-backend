<?php

namespace Modules\Task\Services;

use Modules\Task\Models\Task;

class TaskService
{
    /**
     * Create task and return.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $data)
    {
        return Task::query()->create([
            'title'       => $data['title'],
            'description' => $data['description'],
            'remind_date' => $data['remind_date'],
            'priority'    => $data['priority'],
            'status'      => $data['status'],
        ]);
    }

    /**
     * Update task and return bool.
     *
     * @param array $data
     * @param Task  $task
     *
     * @return bool
     */
    public function update(array $data, Task $task)
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
