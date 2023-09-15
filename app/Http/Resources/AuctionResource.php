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
            'is_wishlist'=> $this->wishlist_count,
            'description' => $this->description,
            'is_publish' => $this->is_publish,
            'auction_type' => $this->type->name,
            'type' => $this->type->value,
            'max_price' => $this->max_price,
            'is_multilot' => $this->baskets->products()->count() > 1,
            'invite_peoples' => $this->invites,
            'price' => $this->baskets ? $this->baskets->price:'',
            'basket' => new BasketResource($this->baskets),
        ];
    }

    public static function selectLst() {
        return [
            'auctions.id',
            'auctions.title',
            'auctions.description',
            'auctions.is_publish',
            'auctions.type',
            'auctions.max_price',
            'auctions.basket_id',
            'auctions.start_date',
            'auctions.end_date',
        ];
    }
}
