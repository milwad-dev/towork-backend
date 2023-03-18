<?php

namespace Modules\Category\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Models\Category;

class CategoryService
{
    /**
     * Store category by array of data.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $data)
    {
        return Category::query()->create([
            'title'   => $data['title'],
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Update category by array of data.
     *
     * @param array $data
     * @param int   $id
     *
     * @return Builder|Model
     */
    public function update(int $id, array $data)
    {
        return tap(Category::query()->where('id', $id))->update([
            'title'   => $data['title'],
            'user_id' => auth()->id(),
        ]);
    }
}
