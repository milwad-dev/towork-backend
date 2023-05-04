<?php

namespace Modules\Task\Http\Controllers;

use Modules\Common\Http\Controllers\Controller;
use Modules\Task\Http\Requests\StoreTaskRequest;
use Modules\Task\Http\Requests\UpdateTaskRequest;
use Modules\Task\Http\Resources\TaskCollectionResource;
use Modules\Task\Models\Task;
use Modules\Task\Repositories\TaskRepoEloquent;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = resolve(TaskRepoEloquent::class)->getLatest()->get();

        return new TaskCollectionResource($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
