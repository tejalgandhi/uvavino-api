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
            'basket_id' => $this->resource->id,
            'wine_name' => $this->resource->wine_name,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
        ];
    }
}
