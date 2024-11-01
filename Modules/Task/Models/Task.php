<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Task\Database\Factories\TaskFactory;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\User\Models\User;

class Task extends Model
{
    use HasFactory;

    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'remind_date',
        'priority',
        'status',
    ];

    /**
     * Set factory for model.
     */
    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }

    // Relations

    /**
     * Relation one-to-many, User model.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Methods

    /**
     * Mark task as done.
     */
    public function markAsDone(): bool
    {
        return $this->update(['status' => TaskStatusEnum::STATUS_ACTIVE->value]);
    }
}
