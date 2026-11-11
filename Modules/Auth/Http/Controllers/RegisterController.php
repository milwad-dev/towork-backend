<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Auth\Enums\AuthCodePurpose;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Services\AuthCodeService;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Services\UserService;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, UserService $users, AuthCodeService $codes): JsonResponse
    {
        $user = $users->create($request->validated());

        $codes->send($user->email, AuthCodePurpose::VerifyEmail);

        return response()->json([
            'data' => [
                'user'  => new UserResource($user),
                'token' => $users->generateToken($user),
            ],
        ], 201);
    }
}
