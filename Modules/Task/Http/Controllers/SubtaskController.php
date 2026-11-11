<?php

namespace Modules\Task\Http\Controllers;

use Illuminate\Http\Response;
use Modules\Common\Http\Controllers\Controller;
use Modules\Task\Http\Requests\SaveSubtaskRequest;
use Modules\Task\Http\Resources\SubtaskResource;
use Modules\Task\Models\Subtask;
use Modules\Task\Models\Task;
use Modules\Task\Services\TaskService;

class SubtaskController extends Controller
{
    public function store(SaveSubtaskRequest $request, Task $task, TaskService $service): SubtaskResource
    {
        $this->authorize('update', $task);

        return new SubtaskResource($service->addSubtask($task, $request->validated('title')));
    }

    public function update(SaveSubtaskRequest $request, Subtask $subtask): SubtaskResource
    {
        $this->authorize('update', $subtask);

        $subtask->update($request->validated());

        return new SubtaskResource($subtask);
    }

    public function destroy(Subtask $subtask): Response
    {
        $this->authorize('delete', $subtask);

        $subtask->delete();

        return response()->noContent();
    }
}
