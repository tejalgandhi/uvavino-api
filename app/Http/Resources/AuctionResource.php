<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\AuctionType;

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
        $user_id = $request->user_id;

        return [
            'id' => $this->id,
            'user_id' => $user_id,
            'country_id' => $this->country_id,
            'country_name' => $this->country_name,
            'title' => $this->title,
            'total_bids' => $this->bids_count,
            'start_date' => $this->start_date ?? '',
            'end_date' =>  $this->end_date ??'',
            'is_wishlist'=> !empty($user_id)? $this->in_wishlist:0,
            'description' => $this->description,
            'is_private' => $this->is_private,
            'auction_type' => $this->type,
            'max_price' => $this->max_price,
            'basket' => new BasketResource($this->baskets),
            'auction_type_name' => AuctionType::tryFrom($this->type)?->label(),
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
            'auctions.basket_id',
            'auctions.start_date',
            'auctions.end_date',
        ];
    }
}
