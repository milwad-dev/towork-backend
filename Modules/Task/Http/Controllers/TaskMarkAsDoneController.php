<?php

namespace Modules\Task\Http\Controllers;

use Modules\Common\Http\Controllers\Controller;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\Task\Models\Task;
use Symfony\Component\HttpFoundation\Response;

class TaskMarkAsDoneController extends Controller
{
    /**
     * Mark task as done.
     */
    public function __invoke(Task $task): \Illuminate\Http\JsonResponse
    {
        if ($task->status !== TaskStatusEnum::STATUS_ACTIVE->value) {
            return response()->json([
                'message' => 'You already done this task.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $task->markAsDone();

        return response()->json([
            'data' => 'The task mark as done successfully.',
        ], Response::HTTP_OK);
    }
}
