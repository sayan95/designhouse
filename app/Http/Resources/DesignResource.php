<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'images' => $this->images,
            'likes' => $this->likes()->count(),
            'is_live' => $this->is_live,
            'description' => $this->description,
            'tag_list' => [
                'tags' => $this->tagArray,
                'tags_normalized' => $this->tagArrayNormalized
            ],
            'team' =>  $this->team ?[
                'name' => $this->team->name,
                'slug' => $this->team->slug
            ] : [],
            'created_at_dates' => [
                'created_at_humans' => $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ],
            'updated_at_dates' => [
                'updated_at_humans' => $this->updated_at->diffForHumans(),
                'updated_at' => $this->updated_at
            ],
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
