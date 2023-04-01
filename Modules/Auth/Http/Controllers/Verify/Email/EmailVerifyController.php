<?php

namespace Modules\Auth\Http\Controllers\Verify\Email;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Modules\Auth\Jobs\SendCodeEmailVerifyJob;
use Modules\Auth\Repositories\VerifyCodeRepoEloquent;
use Modules\Auth\Services\EmailVerifyService;
use Modules\Common\Http\Controllers\Controller;
use Modules\Common\Responses\JsonResponseFacade;
use Modules\User\Repositories\UserRepoEloquent;
use Symfony\Component\HttpFoundation\Response;

class EmailVerifyController extends Controller
{
    /**
     * Send verify email if user is not verify.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function request(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return JsonResponseFacade::forbiddenResponse([ // Already status code is 403
                'data' => [
                    'message' => 'You already verified',
                ],
                'status' => 'error',
            ]);
        }

        $this->sendVerifyEmail();

        return JsonResponseFacade::successResponse([
            'data' => [
                'message' => 'Email verify send successfully',
            ],
            'status' => 'success',
        ]);
    }

    /**
     * Verify user.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        $verifyCode = resolve(VerifyCodeRepoEloquent::class)->findByCode($request->code); // Find code

        if ($verifyCode->created_at > now()->addHour()) { // Check code is expire
            return resolve(EmailVerifyService::class)->deleteEmailVerifyCodeWithReturnResponse($verifyCode);
        }

        $user = resolve(UserRepoEloquent::class)->findByEmail($verifyCode->email); // Find user
        $user->markEmailAsVerified(); // Verify user

        $verifyCode->delete(); // Delete verify code

        return response([
            'data' => [
                'message' => 'You have been successfully verified!',
            ],
            'status' => 'success',
        ]);
    }

    /**
     * Resend email verify code.
     *
     * @throws \Exception
     *
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function resend()
    {
        $verifyCode = resolve(VerifyCodeRepoEloquent::class)->getLastVerifyCodeFromUser(); // Find code

        if (is_null($verifyCode)) {
            return response([
                'data' => [
                    'message' => "You aren't need to verify!!!",
                ],
                'status' => 'error',
            ], Response::HTTP_FORBIDDEN);
        }

        $verifyCode->delete(); // Delete verify code
        $this->sendVerifyEmail();

        return response([
            'data' => [
                'message' => 'Verification code sent successfully.',
            ],
            'status' => 'success',
        ]);
    }

    /**
     * Send email verify.
     *
     * @throws \Exception
     *
     * @return void
     */
    private function sendVerifyEmail()
    {
        $code = resolve(EmailVerifyService::class)->generateCode();

        SendCodeEmailVerifyJob::dispatch(auth()->user(), $code);
    }
}
