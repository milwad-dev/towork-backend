<?php

namespace Modules\Auth\Http\Controllers\Password;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Modules\Auth\Http\Requests\Password\CodeCheckRequest;
use Modules\Auth\Models\ResetCodePassword;
use Modules\Auth\Repositories\ResetCodePasswordRepoEloquent;
use Modules\Common\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class CodeCheckController extends Controller
{
    /**
     * Check reset password code is valid.
     *
     * @param  CodeCheckRequest $request
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(CodeCheckRequest $request)
    {
        $resetCodePassword = resolve(ResetCodePasswordRepoEloquent::class)->findByCode($request->code); // Find code

        if ($resetCodePassword->created_at > now()->addHour()) { // Check
            return $this->deleteResetCodePasswordWithReturnResponse($resetCodePassword);
        }

        return response([
            'code'      => $resetCodePassword->code,
            'message'   => 'The code is valid.'
        ]);
    }

    /**
     * Delete reset code with return response.
     *
     * @param ResetCodePassword $resetCodePassword
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    private function deleteResetCodePasswordWithReturnResponse(ResetCodePassword $resetCodePassword)
    {
        $resetCodePassword->delete();

        return response([
            'message'   => 'The code has expired.',
            'status'    => 'error'
        ], Response::HTTP_GONE);
    }
}
