<?php

namespace Modules\Category\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Models\Category;

/** @mixin Category */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'icon' => $this->icon,
            'tasks_count' => $this->whenCounted('tasks'),
            'open_tasks_count' => $this->whenCounted('open_tasks'),
            'created_at' => $this->created_at,
        ];
    }
}
