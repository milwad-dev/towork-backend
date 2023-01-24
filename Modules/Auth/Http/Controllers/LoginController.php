<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * Login user.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        $field = $request->email;
        $correctField = filter_var($field, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (! Auth::attempt([$correctField => $field, 'password' => $request->password])) {
            return response([
                'data' => ['message' => 'Unauthorised.'],
                'status' => 'error'
            ], Response::HTTP_FORBIDDEN);
        }

        $user = Auth::user();
        return response([
            'data' => [
                'token' => resolve(UserService::class)->generateToken($user, 'login-user'),
                'name'  => $user->name,
                'email' => $user->email
            ],
            'status' => 'success'
        ]);
    }
}
