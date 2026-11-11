<?php

namespace Modules\Task\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Task\Models\Subtask;

/** @mixin Subtask */
class SubtaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'task_id'      => $this->task_id,
            'title'        => $this->title,
            'is_completed' => $this->is_completed,
            'position'     => $this->position,
        ];
    }
}
