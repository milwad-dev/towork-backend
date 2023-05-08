<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Task\Database\Factories\TaskFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * Set factory.
     *
     * @return TaskFactory
     */
    protected static function newFactory()
    {
        return TaskFactory::new();
    }

    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['title', 'description', 'remind_date', 'priority', 'status'];
}
