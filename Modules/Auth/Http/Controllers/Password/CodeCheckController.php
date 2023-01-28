<?php

namespace Modules\Auth\Http\Controllers\Password;

use Modules\Auth\Http\Requests\Password\CodeCheckRequest;
use Modules\Auth\Repositories\ResetCodePasswordRepoEloquent;
use Modules\Common\Http\Controllers\Controller;

class CodeCheckController extends Controller
{
    public function __invoke(CodeCheckRequest $request)
    {
        $resetCodePassword = resolve(ResetCodePasswordRepoEloquent::class)->findByCode($request->code);
    }
}
