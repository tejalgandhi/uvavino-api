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
            'ref_type' => $this->resource->ref_type,
            'ref_id' => $this->resource->ref_id,
            'user_id' => $this->resource->user_id,
            'user_name' => $this->resource->wine_user->username,
            'rating' => $this->resource->rating,
            'review_content' => $this->resource->review_content,

        ];
    }
}
