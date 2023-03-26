<?php

namespace Modules\Auth\Http\Controllers\Verify\Email;

use Illuminate\Http\Request;
use Modules\Common\Http\Controllers\Controller;
use Modules\Common\Responses\JsonResponseFacade;

class EmailVerifyController extends Controller
{
    public function request(Request $request)
    {
        return $request->user()->hasVerifiedEmail() && $request->expectsJson() ?
            JsonResponseFacade::forbiddenResponse([
                'data' => [
                    'message' => 'You already verified'
                ],
                'status' => 'error'
            ]) :
            $this->sendVerifyEmail();
    }

    public function verify()
    {
        // TODO: Implement __invoke() method.
    }

    public function resend()
    {
    }
}
