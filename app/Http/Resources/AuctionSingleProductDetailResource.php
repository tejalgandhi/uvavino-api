<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\AuctionType;

class AuctionSingleProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $product = $request->product;
        return [
            'id' => $this->id,
            'seller_id' => $this->created_by ?? '',
            'seller_name' => $this->wine_user ?$this->wine_user->username :'',
            'seller_created_at' => $this->wine_user ?$this->wine_user->created_at :'',
            'seller_reviews_count' =>  $this->wine_user ?$this->wine_user->countRating():0,
            'seller_ratings' =>  $this->wine_user ?$this->wine_user->averageRating():0,
            'country_id' => $this->country_id,
            'country_name' => $this->country_name,
            'iso_alpha_2' => $this->country_iso_code,
            'flag' =>  $this->country_iso_code ? country_flag($this->country_iso_code) : '',
            'title' => $this->title,
            'total_bids' => $this->bids_count,
            'start_date' => $this->start_date ?? '',
            'end_date' =>  $this->end_date ??'',
            'is_wishlist'=> $this->wishlist_count ?? 0,
            'description' => $this->description,
            'is_publish' => $this->is_publish,
            'auction_type' => $this->type->name,
            'type' => $this->type->value,
            'max_price' => $this->max_price,
            'auction_reviews_count' => $this->countRating(),
            'auction_ratings' => $this->averageRating(),
            'auction_rating_order' => $this->rating_order_count,
            'is_multilot' => $this->baskets->products()->count() > 1,
            'invite_peoples' => $this->invites,
            'price' => $this->baskets ? $this->baskets->price:'',
            'product'=>new ProductResource($product),
        ];
    }

}
