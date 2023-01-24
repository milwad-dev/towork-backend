<?php

namespace Modules\Auth\Http\Controllers;

use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, UserService $userService)
    {
        $user  = $userService->create($request->validated());
        $token = $userService->generateToken($user);

        return response([
            'data' => [
                'user'  => $user,
                'token' => $token
            ]
        ], Response::HTTP_CREATED);
    }
}
