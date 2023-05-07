<?php

namespace Modules\Task\Http\Controllers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Common\Http\Controllers\Controller;
use Modules\Task\Http\Requests\StoreTaskRequest;
use Modules\Task\Http\Requests\UpdateTaskRequest;
use Modules\Task\Http\Resources\TaskResource;
use Modules\Task\Models\Task;
use Modules\Task\Repositories\TaskRepoEloquent;
use Modules\Task\Services\TaskService;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ResourceCollection
     */
    public function index()
    {
        $tasks = resolve(TaskRepoEloquent::class)->getLatest()->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = resolve(TaskService::class)->store($request->validated());

        return new TaskResource($task);
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
        resolve(TaskService::class)->update($request->validated(), $task);

        return new TaskResource($task); // TODO: Refresh new task
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
