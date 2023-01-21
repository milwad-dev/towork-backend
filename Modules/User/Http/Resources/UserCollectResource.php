<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollectResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ]
        ];
    }

    /**
     * With response.
     *
     * @param $request
     * @return array
     */
    public function with($request)
    {
        return ['status' => 'success'];
    }
}
