<?php

namespace Modules\Task\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'remind_date' => $this->remind_date,
            'priority' => $this->priority,
            'status' => $this->status,
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
            'status' => 'success',
        ];
    }
}
