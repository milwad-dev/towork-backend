<?php

namespace Modules\RolePermission\Services;

use Modules\Common\Contracts\Interface\ServicesInterface;
use Modules\Common\Repositories\CommonRepoEloquent;
use Modules\RolePermission\Models\RolePermission;

class RolePermissionService implements ServicesInterface
{
    private $class = RolePermission::class;

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