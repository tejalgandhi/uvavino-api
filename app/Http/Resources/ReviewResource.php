<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'reviewable_type' => $this->resource->reviewable_type,
            'reviewable_id' => $this->resource->reviewable_id,
            'wine_user_id' => $this->resource->wine_user_id,
            'user_name' => $this->resource->wine_user->username,
            'rating' => $this->resource->rating,
            'review_content' => $this->resource->review_content,

        ];
    }
}
