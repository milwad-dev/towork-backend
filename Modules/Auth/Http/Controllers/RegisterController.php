<?php

namespace Modules\Auth\Http\Controllers;

use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * @QA\Post(
     *  path="/api/register",
     *  operationId="registerUser",
     *  tags=["Auth"],
     *  summary="Register user"
     * )
     *
     * Register user.
     *
     * @param RegisterRequest $request
     * @param UserService $userService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request, UserService $userService)
    {
        $user  = $userService->create($request->validated());
        $token = $userService->generateToken($user);

        return response([
            'data' => [
                'user'  => $user,
                'token' => $token
            ],
            'status' => 'success'
        ], Response::HTTP_CREATED);
    }
}
