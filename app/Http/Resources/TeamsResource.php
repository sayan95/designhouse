<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total_members' => $this->members()->count(),
            'slug' => $this->slug,
            'owner' => new UserResource($this->owner),
            'members' => UserResource::collection($this->members),
            'designs' => DesignResource::collection($this->designs)
        ];
    }
}