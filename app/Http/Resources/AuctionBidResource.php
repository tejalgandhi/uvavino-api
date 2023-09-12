<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\AuctionType;

class AuctionBidResource extends JsonResource
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
            'id' => $this->id,
            'wine_user_id' => $this->wine_user_id,
            'username' => $this->wine_user ?$this->wine_user->username:'' ,
            'auction_id' => $this->auction_id,
            'price' => $this->value,
            'timestamp' => $this->timestamp,

        ];
    }

    public static function selectLst() {
        return [
            'auction_bids.id',
            'auction_bids.wine_user_id',
            'auction_bids.auction_id',
            'auction_bids.value',
            'auction_bids.timestamp',

        ];
    }
}
