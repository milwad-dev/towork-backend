<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Repositories\UserRepoEloquent;
use Modules\User\Services\UserService;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, UserRepoEloquent $repo, UserService $users): JsonResponse
    {
        $user = $repo->findByLogin($request->validated('login'));

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            throw ValidationException::withMessages([
                'login' => 'These credentials do not match our records.',
            ]);
        }

        return response()->json([
            'data' => [
                'user' => new UserResource($user),
                'token' => $users->generateToken($user, substr((string) $request->userAgent(), 0, 100) ?: 'web'),
            ],
        ]);
    }
}
