<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;

class UserRepoEloquent
{
    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', strtolower($email))->first();
    }

    /**
     * Find a user by e-mail address or phone number.
     */
    public function findByLogin(string $login): ?User
    {
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        return User::query()->where($field, $field === 'email' ? strtolower($login) : $login)->first();
    }
}
