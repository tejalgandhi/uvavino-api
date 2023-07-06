<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuctionResource extends JsonResource
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
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'is_private' => $this->resource->is_private,
            'type' => $this->resource->type,
            'max_price' => $this->resource->max_price,
            'basket' => new BasketResource($this->resource->baskets),
        ];
    }

    public static function selectLst() {
        return [
            'auctions.id',
            'auctions.title',
            'auctions.description',
            'auctions.is_private',
            'auctions.type',
            'auctions.max_price',
            'auctions.basket_id'
        ];
    }
}
