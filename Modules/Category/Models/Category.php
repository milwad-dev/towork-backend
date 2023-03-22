<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Database\Factories\CategoryFactory;
use Modules\User\Models\User;

class Category extends Model
{
    use HasFactory;

    /**
     * Set factory.
     *
     * @return CategoryFactory
     */
    protected static function newFactory()
    {
        return CategoryFactory::new();
    }

    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['title', 'user_id'];

    // Relations

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
