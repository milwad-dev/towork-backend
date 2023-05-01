<?php

namespace Modules\Task\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'remind_date' => $task->remind_date,
                    'priority' => $task->priority,
                    'status' => $task->status
                ];
            })
        ];
    }

    /**
     * With response.
     *
     * @param Request $request
     * @return array
     */
    public function with(Request $request)
    {
        return [
            'status' => 'success'
        ];
    }
}
