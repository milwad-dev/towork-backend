<?php

namespace Modules\Label\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Label\Database\Factories\LabelFactory;
use Modules\Task\Models\Task;
use Modules\User\Models\User;

#[UseFactory(LabelFactory::class)]
class Label extends Model
{
    /** @use HasFactory<LabelFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'color'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }
}
