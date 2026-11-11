<?php

namespace Modules\Category\Services;

use Modules\Category\Models\Category;
use Modules\User\Models\User;

class CategoryService
{
    public function store(User $user, array $data): Category
    {
        return $user->categories()->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
