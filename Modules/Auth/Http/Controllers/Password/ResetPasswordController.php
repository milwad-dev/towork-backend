<?php

namespace Modules\Auth\Http\Controllers\Password;

use Modules\Auth\Http\Requests\Password\ResetPasswordRequest;
use Modules\Auth\Repositories\ResetCodePasswordRepoEloquent;
use Modules\Auth\Services\ResetPasswordService;
use Modules\Common\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request)
    {
        $resetCodePassword = resolve(ResetCodePasswordRepoEloquent::class)->findByCode($request->code); // Find code
        if ($resetCodePassword->created_at > now()->addHour()) { // Check
            return resolve(ResetPasswordService::class)->deleteResetCodePasswordWithReturnResponse($resetCodePassword);
        }
        

    }
}
