<?php

namespace Modules\Label\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Label\Models\Label;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\User\Models\User;

class LabelRepoEloquent
{
    /**
     * All labels of the user, alphabetically, with task counters.
     */
    public function allFor(User $user): Collection
    {
        return $user->labels()->withCount($this->counts())->orderBy('name')->get();
    }

    public function loadCounts(Label $label): Label
    {
        return $label->loadCount($this->counts());
    }

    private function counts(): array
    {
        return [
            'tasks',
            'tasks as open_tasks_count' => fn (Builder $q) => $q->where('status', '!=', TaskStatusEnum::Done->value),
        ];
    }
}
