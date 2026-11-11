<?php

namespace Modules\Category\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Category\Models\Category;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\User\Models\User;

class CategoryRepoEloquent
{
    /**
     * All categories of the user, alphabetically, with task counters.
     */
    public function allFor(User $user): Collection
    {
        return $user->categories()->withCount($this->counts())->orderBy('name')->get();
    }

    public function loadCounts(Category $category): Category
    {
        return $category->loadCount($this->counts());
    }

    private function counts(): array
    {
        return [
            'tasks',
            'tasks as open_tasks_count' => fn (Builder $q) => $q->where('status', '!=', TaskStatusEnum::Done->value),
        ];
    }
}
