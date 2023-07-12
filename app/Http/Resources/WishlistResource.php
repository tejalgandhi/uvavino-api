<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
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
        $data = [
            'id' => $this->resource->id,
            'type' => $this->resource->type,
            'type_name' => $this->resource->type == Wishlist::AUCTION_TYPE ? 'auction':'product' ,
            'auction_id' => $this->resource->type == Wishlist::AUCTION_TYPE ? $this->resource->ref_id : 0,
            'basket_id' => $this->resource->type == Wishlist::PRODUCT_TYPE ? $this->resource->ref_id : $this->resource->auction->basket_id,
            'title' => $this->resource->type == Wishlist::PRODUCT_TYPE ? $this->resource->basket->title:$this->resource->auction->title,
            'slug' => $this->resource->type == Wishlist::PRODUCT_TYPE ? $this->resource->basket->slug :"-" ,
            'description' => $this->resource->type == Wishlist::PRODUCT_TYPE ? $this->resource->basket->description:$this->resource->auction->description,
        ];

        return $data;
    }
}
