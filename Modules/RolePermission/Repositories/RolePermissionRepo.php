<?php

namespace Modules\RolePermission\Repositories;

use Modules\Common\Contracts\Interface\RepositoriesInterface;
use Modules\Common\Repositories\CommonRepoEloquent;
use Modules\RolePermission\Models\RolePermission;

class RolePermissionRepo implements RepositoriesInterface
{
    private $class = RolePermission::class;

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
