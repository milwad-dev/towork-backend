<?php

namespace Modules\Auth\Services;

use Modules\Auth\Models\Auth;
use Modules\Common\Contracts\Interface\ServicesInterface;
use Modules\Common\Repositories\CommonRepoEloquent;

class AuthService implements ServicesInterface
{
    private $class = Auth::class;

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
