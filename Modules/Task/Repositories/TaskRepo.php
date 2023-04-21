<?php

namespace Modules\Task\Repositories;

use Modules\Common\Contracts\Interface\RepositoriesInterface;
use Modules\Common\Repositories\CommonRepoEloquent;
use Modules\Task\Models\Task;

class TaskRepo implements RepositoriesInterface
{
    private $class = Task::class;

    public function index()
    {

    }

    public function findById($id)
    {

    }

    public function delete($id)
    {

    }

    private function query()
    {
        return CommonRepoEloquent::query($this->class);
    }
}
