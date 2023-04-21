<?php

namespace Modules\Task\Services;

use Modules\Common\Contracts\Interface\ServicesInterface;
use Modules\Task\Models\Task;
use Modules\Common\Repositories\CommonRepoEloquent;

class TaskService implements ServicesInterface
{
    private $class = Task::class;

    public function store($request)
    {
        return $this->query()->create([

        ]);
    }

    public function update($request, $id)
    {
         return $this->query()->whereId($id)->update([

        ]);
    }

    private function query()
    {
        return CommonRepoEloquent::query($this->class);
    }
}
        