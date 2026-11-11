<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Enums\AuthCodePurpose;
use Modules\Auth\Http\Requests\VerifyEmailRequest;
use Modules\Auth\Services\AuthCodeService;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Http\Resources\UserResource;

class EmailVerificationController extends Controller
{
    public function __construct(private readonly AuthCodeService $codes)
    {
    }

    public function send(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Your e-mail address is already verified.'], 409);
        }

        $this->codes->send($user->email, AuthCodePurpose::VerifyEmail);

        return response()->json(['message' => 'A new verification code has been sent to '.$user->email.'.']);
    }

    public function verify(VerifyEmailRequest $request): UserResource
    {
        $user = $request->user();

        if (!$user->hasVerifiedEmail()) {
            if (!$this->codes->verify($user->email, AuthCodePurpose::VerifyEmail, $request->validated('code'))) {
                throw ValidationException::withMessages(['code' => 'This code is invalid or has expired.']);
            }

            $user->markEmailAsVerified();
        }

        return new UserResource($user->fresh());
    }
}
