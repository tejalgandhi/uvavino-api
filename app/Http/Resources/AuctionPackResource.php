<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuctionPackResource extends JsonResource
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
            'slug' => $this->resource->slug,
            'name' => $this->resource->name,
            'qty' => $this->resource->qty,
            'amount' => $this->resource->amount,
            'discount_price' => $this->resource->discount_amount,
            'image' => getFileUploadPath($this->resource->image),
        ];
    }

    public static function selectLst() {
        return [
            'auction_packages.id',
            'auction_packages.slug',
            'auction_packages.name',
            'auction_packages.qty',
            'auction_packages.amount',
            'auction_packages.discount_amount',
            'auction_packages.image',
        ];
    }

}
