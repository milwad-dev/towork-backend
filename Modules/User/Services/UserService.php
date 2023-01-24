<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class UserService
{
    /**
     * Create user by array of data.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return User::query()->create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'],
            'password'  => Hash::make($data['password'])
        ]);
    }

    /**
     * Update user by array of data and id.
     *
     * @param array $data
     * @param int $id
     * @return int
     */
    public function update(array $data, int $id)
    {
        return User::query()->where('id', $id)->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'],
            'password'  => Hash::make($data['password'])
        ]);
    }
}
