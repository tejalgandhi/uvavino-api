<?php

namespace App\Http\Resources;

use App\Enums\OrderType;
use Illuminate\Http\Resources\Json\JsonResource;

class CartDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $type = OrderType::product->className();
        if ($this->resource->cartable_type == $type) {
            $cartable = new BasketResource($this->resource->cartable);
        } else {
            $cartable = new AuctionPackResource($this->resource->cartable);
        }

        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'qty' => $this->qty,
            'amount' => $this->amount,
            'total_amount' => $this->total_amount,
            'cartable' => $cartable
        ];
    }
}
