<?php

namespace Modules\Auth\Repositories;

use Modules\Auth\Models\EmailVerifyCode;

class VerifyCodeRepoEloquent
{
    /**
     * Find by code.
     *
     * @param string $code
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findByCode(string $code)
    {
        return EmailVerifyCode::query()
            ->where('code', $code)
            ->firstOrFail();
    }
}
