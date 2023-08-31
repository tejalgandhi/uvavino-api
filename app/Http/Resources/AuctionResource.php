<?php

namespace App\Http\Resources;

use App\Models\Auction;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'total_bids' => count($this->resource->bids) ?? 0,
            'country_id' => !empty($this->resource->baskets) && !empty($this->resource->baskets->products) && !empty($this->resource->baskets->products[0]) ?$this->resource->baskets->products[0]->country_id: '',
            'country_name' => !empty($this->resource->baskets) && !empty($this->resource->baskets->products) && !empty($this->resource->baskets->products[0]) && $this->resource->baskets->products[0]->country ?$this->resource->baskets->products[0]->country->name: '',
            'start_date' => $this->resource->start_date ?? '',
            'start_time' => $this->resource->start_date ? Carbon::parse($this->resource->start_date)->format('D, d M Y | h:i a ') : '',
            'end_date' =>  $this->resource->end_date ??'',
            'end_time' =>  $this->resource->end_date ? Carbon::parse($this->resource->end_date)->format('D, d M Y | h:i a ') : '',
            'is_wishlist'=> Auth::check()? Wishlist::where(['type'=>Wishlist::AUCTION_TYPE, 'ref_id'=>$this->resource->id, 'wine_user_id'=>Auth::user()->id])->count():0,
            'description' => $this->resource->description,
            'is_private' => $this->resource->is_private,
            'auction_type' => $this->resource->type,
            'auction_type_name' => $this->resource->type == Auction::IS_LIVE_AUCTION_TYPE ? 'Live' : 'Timebased',
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
            'auctions.basket_id',
            'auctions.start_date',
            'auctions.end_date',
        ];
    }
}
