<?php

namespace Modules\Auth\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Models\ResetCodePassword;

class ResetPasswordService
{
    /**
     * Delete by email.
     *
     * @param string $email
     * @return mixed
     */
    public function deleteByEmail(string $email)
    {
        return ResetCodePassword::query()->where('email', $email)->delete();
    }

    /**
     * Generate code.
     *
     * @return int
     * @throws \Exception
     */
    public function generateCode()
    {
        return random_int(100000, 999999);
    }

    /**
     * Store reset password by array of data.
     *
     * @param array $data
     * @return Builder|Model
     */
    public function store(array $data)
    {
        return ResetCodePassword::query()->create([
            'email' => $data['email'],
            'code'  => $data['code']
        ]);
    }
}
