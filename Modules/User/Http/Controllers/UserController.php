<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Http\Requests\UserStoreRequest;
use Modules\User\Http\Resources\UserCollectResource;
use Modules\User\Http\Resources\UserSingleResource;
use Modules\User\Repositories\UserRepoEloquent;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserRepoEloquent $userRepoEloquent
    ) {}

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
