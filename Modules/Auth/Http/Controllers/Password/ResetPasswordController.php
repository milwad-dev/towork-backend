<?php

namespace Modules\Auth\Http\Controllers\Password;

use Modules\Auth\Http\Requests\Password\ResetPasswordRequest;
use Modules\Auth\Repositories\ResetCodePasswordRepoEloquent;
use Modules\Auth\Services\ResetPasswordService;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Repositories\UserRepoEloquent;

class ResetPasswordController extends Controller
{
    /**
     * Reset password.
     *
     * @param  ResetPasswordRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(ResetPasswordRequest $request)
    {
        $resetCodePassword = resolve(ResetCodePasswordRepoEloquent::class)->findByCode($request->code); // Find code

        if ($resetCodePassword->created_at > now()->addHour()) { // Check
            return resolve(ResetPasswordService::class)
                ->deleteResetCodePasswordWithReturnResponse($resetCodePassword);
        }

        $user = resolve(UserRepoEloquent::class)->findByEmail($resetCodePassword->email); // Find user
        $user->update(['password' => $request->only('password')]); // Update password

        $resetCodePassword->delete(); // Delete reset code password

        return response([
            'data' => [
                'message' => 'Password has been successfully updated'
            ],
            'status' => 'success'
        ]);
    }
}
