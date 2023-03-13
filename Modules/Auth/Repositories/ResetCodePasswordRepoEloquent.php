<?php

namespace Modules\Auth\Repositories;

use Modules\Auth\Models\ResetCodePassword;

class ResetCodePasswordRepoEloquent
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
        return ResetCodePassword::query()
            ->where('code', $code)
            ->firstOrFail();
    }
}
