<?php

namespace Modules\Category\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Models\Category;

class CategoryRepoEloquent
{
    /**
     * Get the latest categories with filter by user_id.
     *
     * @return Builder
     */
    public function getLatest()
    {
        return Category::query()
            ->with('user')
            ->latest();
    }

    /**
     * Find or fail category by id.
     *
     * @param int $id
     *
     * @return Builder|null|Category|Model
     */
    public function findById(int $id)
    {
        return Category::query()->findOrFail($id);
    }
}
