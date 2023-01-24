<?php

namespace Modules\Auth\Repositories;

use Modules\Auth\Models\Auth;
use Modules\Common\Contracts\Interface\RepositoriesInterface;
use Modules\Common\Repositories\CommonRepoEloquent;

class AuthRepo implements RepositoriesInterface
{
    private $class = Auth::class;

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
