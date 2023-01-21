<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Http\Requests\UserStoreRequest;
use Modules\User\Http\Resources\UserCollectResource;
use Modules\User\Http\Resources\UserSingleResource;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    /**
     * Display a listing of the resource.
     *
     * @return UserCollectResource
     */
    public function index()
    {
        $users = $this->repoEloquent->getLatest()->paginate(10);

        return new UserCollectResource($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserStoreRequest $request
     * @return UserSingleResource
     */
    public function store(UserStoreRequest $request)
    {
        $user = $this->userService->store($request->validated());

        return new UserSingleResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
