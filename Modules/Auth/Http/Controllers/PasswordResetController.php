<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Enums\AuthCodePurpose;
use Modules\Auth\Http\Requests\CheckResetCodeRequest;
use Modules\Auth\Http\Requests\ForgotPasswordRequest;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Modules\Auth\Services\AuthCodeService;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Repositories\UserRepoEloquent;

class PasswordResetController extends Controller
{
    public function __construct(
        private readonly AuthCodeService $codes,
        private readonly UserRepoEloquent $users,
    ) {
    }

    /**
     * Send a reset code. Always answers 200 so e-mail addresses can't be enumerated.
     */
    public function send(ForgotPasswordRequest $request): JsonResponse
    {
        if ($this->users->findByEmail($request->validated('email'))) {
            $this->codes->send($request->validated('email'), AuthCodePurpose::ResetPassword);
        }

        return response()->json([
            'message' => 'If an account exists for that e-mail, we sent a 6-digit code to it.',
        ]);
    }

    public function check(CheckResetCodeRequest $request): JsonResponse
    {
        $this->ensureValidCode($request->validated('email'), $request->validated('code'), consume: false);

        return response()->json(['message' => 'The code is valid.']);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $user = $this->users->findByEmail($request->validated('email'));

        $this->ensureValidCode($request->validated('email'), $request->validated('code'), consume: (bool) $user);

        $user->update(['password' => $request->validated('password')]);
        $user->tokens()->delete(); // sign out everywhere

        return response()->json(['message' => 'Your password has been reset. You can sign in now.']);
    }

    private function ensureValidCode(string $email, string $code, bool $consume): void
    {
        if (!$this->codes->verify($email, AuthCodePurpose::ResetPassword, $code, $consume)) {
            throw ValidationException::withMessages(['code' => 'This code is invalid or has expired.']);
        }
    }
}
