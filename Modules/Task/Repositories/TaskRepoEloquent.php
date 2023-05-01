<?php

namespace Modules\Task\Repositories;

use Modules\Task\Models\Task;

class TaskRepoEloquent
{
    public function getLatest()
    {
        return Task::query()->latest();
    }
}
