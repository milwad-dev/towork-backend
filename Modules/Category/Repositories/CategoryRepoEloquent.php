<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\Category;

class CategoryRepoEloquent
{
    /**
     * Get the latest categories with filter by user_id.
     *
     * @param int $user_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getLatest(int $user_id)
    {
        return Category::query()
            ->with('user')
            ->where('user_id', $user_id)
            ->latest();
    }
}
