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
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'total_bids' => count($this->resource->bids),
            'country_id' => !empty($this->resource->baskets) && !empty($this->resource->baskets->products) && !empty($this->resource->baskets->products[0]) ?$this->resource->baskets->products[0]->country_id: '',
            'country_name' => !empty($this->resource->baskets) && !empty($this->resource->baskets->products) && !empty($this->resource->baskets->products[0]) && $this->resource->baskets->products[0]->country ?$this->resource->baskets->products[0]->country->name: '',
            'start_date' => $this->resource->start_date ?? '',
            'start_time' => $this->resource->start_date ? Carbon::parse($this->resource->start_date)->format(config('constant.date_format')) : '',
            'end_date' =>  $this->resource->end_date ??'',
            'end_time' =>  $this->resource->end_date ? Carbon::parse($this->resource->end_date)->format(config('constant.date_format')) : '',
            'is_wishlist'=> !empty($user_id)? Wishlist::where(['type'=>Wishlist::AUCTION_TYPE, 'ref_id'=>$this->resource->id, 'wine_user_id'=>$user_id])->count():0,
            'description' => $this->resource->description,
            'is_publish' => $this->resource->is_publish,
            'auction_type' => $this->resource->type,
            'max_price' => $this->resource->max_price,
            'basket' => new BasketResource($this->resource->baskets),
            'auction_type_name' => AuctionType::tryFrom($this->resource->type)?->label(),
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
