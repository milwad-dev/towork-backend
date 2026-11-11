<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Category\Models\Category;
use Modules\Label\Models\Label;
use Modules\Task\Database\Factories\TaskFactory;
use Modules\Task\Enums\TaskPriorityEnum;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\User\Models\User;

/**
 * @property TaskStatusEnum $status
 * @property TaskPriorityEnum $priority
 */
#[UseFactory(TaskFactory::class)]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'due_time',
        'remind_at',
        'completed_at',
        'position',
    ];

    protected $attributes = [
        'status' => 'todo',
        'priority' => 2,
        'position' => 0,
    ];

    protected function casts(): array
    {
        return [
            'status' => TaskStatusEnum::class,
            'priority' => TaskPriorityEnum::class,
            'due_date' => 'date:Y-m-d',
            'remind_at' => 'datetime',
            'reminded_at' => 'datetime',
            'completed_at' => 'datetime',
            'position' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Task $task) {
            // Keep `completed_at` in sync with the status.
            if ($task->isDirty('status')) {
                $task->completed_at = $task->status === TaskStatusEnum::Done
                    ? ($task->completed_at ?? now())
                    : null;
            }

            // A new reminder time re-arms the reminder.
            if ($task->isDirty('remind_at')) {
                $task->reminded_at = null;
            }
        });
    }

    // Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('position')->orderBy('id');
    }

    // Scopes

    public function scopeOpen(Builder $query): void
    {
        $query->where('status', '!=', TaskStatusEnum::Done->value);
    }

    public function scopeOverdue(Builder $query, string $today): void
    {
        $query->open()->whereNotNull('due_date')->whereDate('due_date', '<', $today);
    }

    // Helpers

    public function isDone(): bool
    {
        return $this->status === TaskStatusEnum::Done;
    }

    public function isOverdue(): bool
    {
        return ! $this->isDone()
            && $this->due_date !== null
            && $this->due_date->toDateString() < client_now()->toDateString();
    }
}
