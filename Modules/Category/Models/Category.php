<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class Category extends Model
{
    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['title', 'user_id'];

    # Relations

    /**
     * Relation one-to-many, User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
