<?php

namespace Modules\Task\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\Task\Models\Subtask;
use Modules\Task\Models\Task;
use Modules\Task\Repositories\TaskRepoEloquent;
use Modules\User\Models\User;

class TaskService
{
    public function __construct(private readonly TaskRepoEloquent $repo) {}

    public function store(User $user, array $data): Task
    {
        return DB::transaction(function () use ($user, $data) {
            $status = $data['status'] ?? TaskStatusEnum::Todo->value;

            /** @var Task $task */
            $task = $user->tasks()->create([
                ...Arr::except($data, ['label_ids', 'subtasks']),
                'status' => $status,
                'position' => $this->repo->nextPosition($user, $status),
            ]);

            if (isset($data['label_ids'])) {
                $task->labels()->sync($data['label_ids']);
            }

            foreach (array_values($data['subtasks'] ?? []) as $i => $title) {
                $task->subtasks()->create(['title' => $title, 'position' => $i]);
            }

            return $this->repo->loadDetails($task);
        });
    }

    public function update(Task $task, array $data): Task
    {
        return DB::transaction(function () use ($task, $data) {
            $task->fill(Arr::except($data, ['label_ids']));

            // Moving to another column puts the task at the bottom of it.
            if ($task->isDirty('status') && ! array_key_exists('position', $data)) {
                $task->position = $this->repo->nextPosition($task->user, $task->status->value);
            }

            // Clearing the date also clears the time.
            if (array_key_exists('due_date', $data) && $data['due_date'] === null) {
                $task->due_time = null;
            }

            $task->save();

            if (array_key_exists('label_ids', $data)) {
                $task->labels()->sync($data['label_ids']);
            }

            return $this->repo->loadDetails($task);
        });
    }

    /**
     * Toggle between done and todo.
     */
    public function toggle(Task $task): Task
    {
        return $this->update($task, [
            'status' => $task->isDone() ? TaskStatusEnum::Todo->value : TaskStatusEnum::Done->value,
        ]);
    }

    /**
     * Persist a Kanban drag & drop: [{id, status, position}, ...].
     */
    public function reorder(User $user, array $items): void
    {
        DB::transaction(function () use ($user, $items) {
            $tasks = $user->tasks()->whereKey(array_column($items, 'id'))->get()->keyBy('id');

            foreach ($items as $item) {
                $task = $tasks->get($item['id']);
                $task?->fill(['status' => $item['status'], 'position' => $item['position']])->save();
            }
        });
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function addSubtask(Task $task, string $title): Subtask
    {
        return $task->subtasks()->create([
            'title' => $title,
            'position' => (int) $task->subtasks()->max('position') + 1,
        ]);
    }
}
