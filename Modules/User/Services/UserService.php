<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class UserService
{
    /**
     * Store user by array of data.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $data)
    {
        return User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }
}
