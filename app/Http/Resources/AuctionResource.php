<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\AuctionType;
use App\Enums\InviteStatus;
use Illuminate\Support\Facades\Log;


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
        $user_id = request()->user_id;
        $wine_user_id = request()->wine_user_id;
        $user_latest_time = !empty($user_id)? $this->resource->userLatestBid($user_id):'';
        $user_latest_invite = !empty($wine_user_id)? $this->resource->userLatestInvite():'';
        return [
            'id' => $this->id,
            'user_latest_bid' => !empty($user_latest_time)?$user_latest_time->value:'',
            'user_auction_status' => !empty($user_latest_time) && $user_latest_time->id == $this->winner_bid?'Won':'Lose',
            'user_invite_status' => !empty($user_latest_invite)?$user_latest_invite->status:'',
            'user_invite_status_string' => !empty($user_latest_invite)?$user_latest_invite->status->label():'',
            'current_bid' => $this->resource->bidRaised(),
            'next_bid' => $this->resource->nextMinBid(),
            'quick_bids' => $this->resource->quickBid(),
            'user_id' => $user_id,
            'country_id' => $this->resource->country_id,
            'country_name' => $this->resource->country_name,
            'iso_alpha_2' => $this->resource->country_iso_code,
            'flag' =>  $this->country_iso_code ? country_flag($this->country_iso_code) : '',
            'title' => $this->title,
            'total_bids' => $this->bids_count,
            'start_date' => $this->start_date ?? '',
            'end_date' =>  $this->end_date ??'',
            'is_wishlist'=> $this->wishlist_count,
            'description' => $this->description,
            'is_publish' => $this->is_publish,
            'is_private' => $this->is_private,
            'is_ended' => $this->is_ended,
            'created_by' => $this->created_by,
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
            'auctions.is_private',
            'auctions.type',
            'auctions.max_price',
            'auctions.basket_id',
            'auctions.start_date',
            'auctions.end_date',
            'auctions.created_by',
        ];
    }
}
