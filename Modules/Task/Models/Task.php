<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Task\Database\Factories\TaskFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'remind_date',
        'priority',
        'status'
    ];

    /**
     * Set factory for model.
     */
    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }
}
