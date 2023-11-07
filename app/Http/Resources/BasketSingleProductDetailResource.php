<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\AuctionType;

class BasketSingleProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $product =  request()->product;
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'is_wishlist'=> $this->wishlist_count,
            'stock' => $this->resource->stock,
            'price' => $this->resource->price,
            'weight' => $this->resource->weight,
            'dimensions' => $this->resource->dimensions,
            'seller_id' => $this->resource->created_by ?? '',
            'seller_name' => $this->resource->wine_user ?$this->wine_user->username :'',
            'seller_created_at' => $this->resource->wine_user ?$this->wine_user->created_at :'',
            'seller_reviews_count' =>  $this->resource->wine_user ?$this->wine_user->countRating():0,
            'seller_ratings' =>  $this->resource->wine_user ?$this->resource->wine_user->averageRating():0,
            'country_id' => $this->resource->country_id,
            'country_name' => $this->resource->country_name,
            'iso_alpha_2' => $this->resource->country_iso_code,
            'flag' =>  $this->resource->country_iso_code ? country_flag($this->resource->country_iso_code) : '',
            'created_by' => $this->resource->created_by,
            'is_multilot' => $this->resource->products()->count() > 1 ,
            'products' => new ProductResource($product),
        ];
    }

}
