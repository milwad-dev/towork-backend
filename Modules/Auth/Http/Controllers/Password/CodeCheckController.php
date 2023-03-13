<?php

namespace Modules\Auth\Http\Controllers\Password;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Modules\Auth\Http\Requests\Password\CodeCheckRequest;
use Modules\Auth\Repositories\ResetCodePasswordRepoEloquent;
use Modules\Auth\Services\ResetPasswordService;
use Modules\Common\Http\Controllers\Controller;

class CodeCheckController extends Controller
{
    /**
     * Check reset password code is valid.
     *
     * @param CodeCheckRequest $request
     *
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(CodeCheckRequest $request)
    {
        $resetCodePassword = resolve(ResetCodePasswordRepoEloquent::class)->findByCode($request->code); // Find code

        if ($resetCodePassword->created_at > now()->addHour()) { // Check
            return resolve(ResetPasswordService::class)->deleteResetCodePasswordWithReturnResponse($resetCodePassword);
        }

        return response([
            'data' => [
                'code'      => $resetCodePassword->code,
                'message'   => 'The code is valid.',
            ],
            'status' => 'success',
        ]);
    }
}
