<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Http\Requests\DeleteAccountRequest;
use Modules\User\Http\Requests\UpdatePasswordRequest;
use Modules\User\Http\Requests\UpdateProfileRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Services\UserService;

class ProfileController extends Controller
{
    public function __construct(private readonly UserService $users) {}

    public function show(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function update(UpdateProfileRequest $request): UserResource
    {
        return new UserResource($this->users->updateProfile($request->user(), $request->validated()));
    }

    public function updatePassword(UpdatePasswordRequest $request): Response
    {
        $this->users->updatePassword($request->user(), $request->validated('password'));

        return response()->noContent();
    }

    public function destroy(DeleteAccountRequest $request): Response
    {
        $this->users->delete($request->user());

        return response()->noContent();
    }
}
