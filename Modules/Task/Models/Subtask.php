<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Task\Database\Factories\SubtaskFactory;

#[UseFactory(SubtaskFactory::class)]
class Subtask extends Model
{
    /** @use HasFactory<SubtaskFactory> */
    use HasFactory;

    protected $fillable = ['task_id', 'title', 'is_completed', 'position'];

    protected $attributes = [
        'is_completed' => false,
        'position'     => 0,
    ];

    protected $touches = ['task'];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'position'     => 'integer',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
