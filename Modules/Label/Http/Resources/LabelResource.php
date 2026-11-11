<?php

namespace Modules\Label\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Label\Models\Label;

/** @mixin Label */
class LabelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'color'            => $this->color,
            'tasks_count'      => $this->whenCounted('tasks'),
            'open_tasks_count' => $this->whenCounted('open_tasks'),
            'created_at'       => $this->created_at,
        ];
    }
}
