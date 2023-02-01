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
            'wine_user_id' => $this->resource->wine_user_id,
            'product_id' => $this->resource->product_id,
            'wine_name' => $this->resource->product->wine_name,
            'slug' => $this->resource->product->slug,
            'description' => $this->resource->product->description,
        ];
    }
}
