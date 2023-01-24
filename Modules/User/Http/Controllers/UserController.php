<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Common\Http\Controllers\Controller;
use Modules\User\Http\Resources\UserCollectResource;
use Modules\User\Repositories\UserRepoEloquent;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserRepoEloquent $userRepoEloquent
    ) {}

    /**
     * @OA\Get(
     *      path="/users",
     *      operationId="getLatestUsers",
     *      tags={"Users"},
     *      summary="Get Latest users with paginate",
     *      description="Return latest user with paginate in specific json format.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProjectResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
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
