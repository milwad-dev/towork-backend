<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;

class UserRepoEloquent
{
    /**
     * Get latest users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getLatest()
    {
        return User::query()->latest();
    }

    public function findByEmail(string $email)
    {
        return User::query()->where('email', $email)->firstOrFail();
    }
}
