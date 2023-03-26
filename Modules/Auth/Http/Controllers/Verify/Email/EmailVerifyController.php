<?php

namespace Modules\Auth\Http\Controllers\Verify\Email;

use Illuminate\Http\Request;
use Modules\Auth\Jobs\SendCodeEmailVerifyJob;
use Modules\Auth\Services\EmailVerifyService;
use Modules\Common\Http\Controllers\Controller;
use Modules\Common\Responses\JsonResponseFacade;

class EmailVerifyController extends Controller
{
    public function request(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return JsonResponseFacade::forbiddenResponse([
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

    public function verify()
    {
        // TODO: Implement __invoke() method.
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
