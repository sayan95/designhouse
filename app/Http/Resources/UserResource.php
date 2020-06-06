<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "id" => $this->id,
            "username" => $this->username,
            "name" => $this->name,
            "email" => $this->email,
            "designs" => DesignResource::collection(
                $this->whenLoaded('designs')
            ),
            "create_dates" => [
                'created_at_humans'=>$this->created_at->diffForHumans(),
                'created_at'=>$this->created_at
            ],
            "tagline" => $this->tagline,
            "about" => $this->about,
            "location" => $this->location,
            "formatted_address" => $this->formatted_address,
            "available_to_hire" => $this->available_to_hire,
        ];
    }
}
