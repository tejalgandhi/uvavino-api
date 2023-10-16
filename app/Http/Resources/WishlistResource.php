<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
use App\Enums\WishlistType;
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
        $type = WishlistType::product->className();
        $auction_type = WishlistType::auction->className();
        $auction_id = $basket_id = $auction_pack_id = 0;
        if ($this->resource->wishlistable_type == $type) {
            $basket_id = $this->resource->wishlistable_id;
            $items = new BasketResource($this->resource->wishlistable);

        } else if ($this->resource->wishlistable_type == $auction_type) {
            $auction_id = $this->resource->wishlistable_id;
            $items = new AuctionResource($this->resource->wishlistable);
        } else {
            $auction_pack_id = $this->resource->wishlistable_id;
            $items = new AuctionPackResource($this->resource->wishlistable);
        }
        $data = [
            'id' => $this->resource->id,
            'type' => WishlistType::fromClassName($this->resource->wishlistable_type),
            'auction_id' => $auction_id,
            'basket_id' => $basket_id,
            'auction_pack_id' => $auction_pack_id,
            'items'=>$items
        ];

        return $data;
    }
}
