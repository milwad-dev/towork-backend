<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Services\UserService;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = resolve(UserService::class)->createUser($request->validated());
    }
}
