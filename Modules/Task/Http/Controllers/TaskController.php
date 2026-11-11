<?php

namespace Modules\Task\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Common\Http\Controllers\Controller;
use Modules\Task\Http\Requests\ListTasksRequest;
use Modules\Task\Http\Requests\ReorderTasksRequest;
use Modules\Task\Http\Requests\StoreTaskRequest;
use Modules\Task\Http\Requests\UpdateTaskRequest;
use Modules\Task\Http\Resources\TaskResource;
use Modules\Task\Models\Task;
use Modules\Task\Repositories\TaskRepoEloquent;
use Modules\Task\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskRepoEloquent $repo,
        private readonly TaskService $service,
    ) {
    }

    public function index(ListTasksRequest $request): AnonymousResourceCollection
    {
        return TaskResource::collection($this->repo->paginate($request->user(), $request->validated()));
    }

    public function store(StoreTaskRequest $request): TaskResource
    {
        return new TaskResource($this->service->store($request->user(), $request->validated()));
    }

    public function show(Task $task): TaskResource
    {
        $this->authorize('view', $task);

        return new TaskResource($this->repo->loadDetails($task));
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $this->authorize('update', $task);

        return new TaskResource($this->service->update($task, $request->validated()));
    }

    public function destroy(Task $task): Response
    {
        $this->authorize('delete', $task);

        $this->service->delete($task);

        return response()->noContent();
    }

    public function toggle(Task $task): TaskResource
    {
        $this->authorize('update', $task);

        return new TaskResource($this->service->toggle($task));
    }

    public function reorder(ReorderTasksRequest $request): Response
    {
        $this->service->reorder($request->user(), $request->validated('tasks'));

        return response()->noContent();
    }
}
