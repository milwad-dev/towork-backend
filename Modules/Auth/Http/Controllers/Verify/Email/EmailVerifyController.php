<?php

namespace Modules\Auth\Http\Controllers\Verify\Email;

use Illuminate\Http\Request;
use Modules\Auth\Jobs\SendCodeEmailVerifyJob;
use Modules\Auth\Repositories\VerifyCodeRepoEloquent;
use Modules\Auth\Services\EmailVerifyService;
use Modules\Auth\Services\ResetPasswordService;
use Modules\Common\Http\Controllers\Controller;
use Modules\Common\Responses\JsonResponseFacade;
use Modules\User\Repositories\UserRepoEloquent;

class EmailVerifyController extends Controller
{
    public function request(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return JsonResponseFacade::forbiddenResponse([ // Already status code is 403
                'data' => [
                    'message' => 'You already verified'
                ],
                'status' => 'error'
            ]);
        }

        $this->sendVerifyEmail();

        return JsonResponseFacade::successResponse([
            'data' => [
                'message' => 'Email verify send successfully'
            ],
            'status' => 'success'
        ]);
    }

    public function verify(Request $request)
    {
        $verifyCode = resolve(VerifyCodeRepoEloquent::class)->findByCode($request->code); // Find code

        if ($verifyCode->created_at > now()->addHour()) { // Check code is expire
            return resolve(EmailVerifyService::class)->deleteEmailVerifyCodeWithReturnResponse($verifyCode);
        }

        $user = resolve(UserRepoEloquent::class)->findByEmail($verifyCode->email); // Find user
        $user->email_verified_at = now();
        $user->save();

        $verifyCode->delete(); // Delete verify code

        return response([
            'data' => [
                'message' => 'You have been successfully verified!',
            ],
            'status' => 'success',
        ]);
    }

    public function resend()
    {
    }

    /**
     * Send email verify.
     *
     * @return void
     *
     * @throws \Exception
     */
    private function sendVerifyEmail()
    {
        $code = resolve(EmailVerifyService::class)->generateCode();

        SendCodeEmailVerifyJob::dispatch($code, auth()->user()->email);
    }
}
