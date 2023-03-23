<?php

namespace Modules\User\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class UserRepoEloquent
{
    /**
     * Get latest users.
     *
     * @return User|Model
     */
    public function query()
    {
        return new User();
    }

    /**
     * Get the latest users.
     *
     * @return Model|User
     */
    public function getLatest()
    {
        return $this->query()->latest();
    }

    /**
     * Find user by email.
     *
     * @param  string  $email
     *
     * @return User
     */
    public function findByEmail(string $email)
    {
        return $this->query()->where('email', $email)->firstOrFail();
    }

    /**
     * Find user by id.
     *
     * @param  int  $id
     *
     * @return bool|mixed|null
     */
    public function findById(int $id)
    {
        return $this->query()->findOrFail($id);
    }
}
