<?php

namespace Modules\Task\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Task\Models\Task;

/** @mixin Task */
class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'due_time' => $this->due_time ? substr($this->due_time, 0, 5) : null,
            'remind_at' => $this->remind_at,
            'completed_at' => $this->completed_at,
            'position' => $this->position,
            'is_overdue' => $this->isOverdue(),
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'color' => $this->category->color,
                'icon' => $this->category->icon,
            ] : null),
            'labels' => $this->whenLoaded('labels', fn () => $this->labels->map(fn ($label) => [
                'id' => $label->id,
                'name' => $label->name,
                'color' => $label->color,
            ])->values()),
            'subtasks' => SubtaskResource::collection($this->whenLoaded('subtasks')),
            'subtasks_count' => $this->whenCounted('subtasks'),
            'completed_subtasks_count' => $this->whenCounted('completed_subtasks'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
