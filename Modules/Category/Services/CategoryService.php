<?php

namespace Modules\Category\Services;

use Modules\Category\Models\Category;

class CategoryService
{
    /**
     * Store category by array of data.
     *
     * @param  array $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $data)
    {
        return Category::query()->create([
            'title' => $data['title'],
            'user_id' => auth()->id(),
        ]);
    }
}
