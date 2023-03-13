<?php

namespace Modules\User\Http\Controllers;

use Modules\Common\Http\Controllers\Controller;
use Modules\User\Http\Requests\UserUpdateRequest;
use Modules\User\Http\Resources\UserCollectResource;
use Modules\User\Repositories\UserRepoEloquent;
use Modules\User\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService      $userService,
        private readonly UserRepoEloquent $userRepoEloquent
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return UserCollectResource
     */
    public function index()
    {
        $users = $this->userRepoEloquent->getLatest()->paginate(10);

        return new UserCollectResource($users);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request,int $id)
    {
        $this->userRepoEloquent->update($request->validated(), $id);

        return response([
            'data' => [
                'message' => 'user updated successfully'
            ],
            'status' => 'success'
        ], Response::HTTP_ACCEPTED);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->userRepoEloquent->destroy($id);

        return response([
            'data' => [
                'message' => 'user deleted successfully'
            ],
            'status' => 'success'
        ]);
    }
}
