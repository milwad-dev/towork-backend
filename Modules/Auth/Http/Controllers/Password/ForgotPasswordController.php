<?php

namespace Modules\Auth\Http\Controllers\Password;

use Modules\Auth\Http\Requests\ForgotPasswordRequest;
use Modules\Auth\Jobs\SendCodeResetPasswordJob;
use Modules\Auth\Services\ResetPasswordService;
use Modules\Common\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    /**
     * Send code for reset password.
     *
     * @param ForgotPasswordRequest $request
     * @param ResetPasswordService $resetPasswordService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(ForgotPasswordRequest $request, ResetPasswordService $resetPasswordService)
    {
        $resetPasswordService->deleteByEmail($request->email); // Delete all old code that user send before.
        $code = $resetPasswordService->store($request->all()); // Store code.
        SendCodeResetPasswordJob::dispatch($request->email, $code->code); // Send email

        return response([
            'data' => ['message' => 'Forgot password email sent successfully.'],
            'status' => 'success'
        ]);
    }
}
