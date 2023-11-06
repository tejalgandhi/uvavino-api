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
        $user_latest_time = !empty($user_id)? $this->resource->userLatestBid($user_id):'';
        $user_auto_bid = !empty($user_id)? $this->resource->userAutoBid($user_id):'';
        $user_latest_invite = !empty($user_id)? $this->resource->userLatestInvite($user_id):'';
        $user_auction_status = $this->resource->userAuctionStatus($user_latest_time);

        return [
            'id' => $this->resource->id,
            'user_latest_bid' => !empty($user_latest_time)?$user_latest_time->value:'',
            'user_automatic_bid' => !empty($user_auto_bid)?$user_auto_bid->max_limit:'',
            'user_auction_status' => $user_auction_status,
            'user_invite_status' => !empty($user_latest_invite)?$user_latest_invite->status:'',
            'user_invite_status_string' => !empty($user_latest_invite)?$user_latest_invite->status->label():'',
            'current_bid' => $this->resource->bidRaised(),
            'next_bid' => $this->resource->nextMinBid(),
            'quick_bids' => $this->resource->quickBid(),
            'user_id' => $user_id,
            'country_id' => $this->resource->country_id,
            'country_name' => $this->resource->country_name,
            'iso_alpha_2' => $this->resource->country_iso_code,
            'flag' =>  $this->resource->country_iso_code ? country_flag($this->resource->country_iso_code) : '',
            'title' => $this->resource->title,
            'total_bids' => $this->resource->bids_count,
            'start_date' => $this->resource->start_date ?? '',
            'end_date' =>  $this->resource->end_date ??'',
            'last_bid_at' =>  $this->resource->last_bid_at ??'',
            'live_time_interval' =>  $this->resource->live_time_interval,
            'is_wishlist'=> $this->resource->wishlist_count,
            'description' => $this->resource->description,
            'is_publish' => $this->resource->is_publish,
            'is_private' => $this->resource->is_private,
            'is_ended' => $this->resource->is_ended,
            'created_by' => $this->resource->created_by,
            'auction_type' => $this->resource->type->name,
            'type' => $this->resource->type->value,
            'max_price' => $this->resource->max_price,
            'is_multilot' => $this->resource->baskets->products()->count() > 1,
            'invite_peoples' => $this->resource->invites,
            'price' => $this->resource->baskets ? $this->resource->baskets->price:'',
            'basket' => new BasketResource($this->resource->baskets),
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
            'auctions.is_ended',
            'auctions.max_price',
            'auctions.basket_id',
            'auctions.start_date',
            'auctions.end_date',
            'auctions.created_by',
        ];
    }
}
