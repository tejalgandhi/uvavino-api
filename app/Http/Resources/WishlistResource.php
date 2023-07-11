<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
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
            'basket_id' => $this->resource->basket_id,
            'title' => $this->resource->basket->title,
            'slug' => $this->resource->basket->slug,
            'description' => $this->resource->basket->description,
        ];
    }
}
