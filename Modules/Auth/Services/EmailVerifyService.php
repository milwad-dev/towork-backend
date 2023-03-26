<?php

namespace Modules\Auth\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Models\EmailVerifyCode;
use Symfony\Component\HttpFoundation\Response;

class EmailVerifyService
{
    /**
     * Delete by email.
     *
     * @param string $email
     *
     * @return mixed
     */
    public function deleteByEmail(string $email)
    {
        return EmailVerifyCode::query()
            ->where('email', $email)
            ->delete();
    }

    /**
     * Generate code.
     *
     * @throws \Exception
     *
     * @return int
     */
    public function generateCode()
    {
        return random_int(100000, 999999);
    }

    /**
     * Store reset password by array of data.
     *
     * @param array $data
     *
     * @return Builder|Model
     */
    public function store(array $data)
    {
        return EmailVerifyCode::query()->create([
            'email'      => $data['email'],
            'code'       => $data['code'],
            'created_at' => now(),
        ]);
    }

    /**
     * Delete reset code with return response.
     *
     * @param EmailVerifyCode $emailVerifyCode
     *
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteEmailVerifyCodeWithReturnResponse(EmailVerifyCode $emailVerifyCode)
    {
        $emailVerifyCode->delete();

        return response([
            'message'   => 'The code has expired.',
            'status'    => 'error',
        ], Response::HTTP_GONE);
    }
}
