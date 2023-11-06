<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
//            'wine_user' => new WineUserResource($this->wine_user),
            'wine_user' => $this->resource->wine_user_id,
            'seller' => $this->resource->seller_id ? $this->resource->seller :'',
            'request_no' => $this->resource->order_no,
            'request_date' => $this->resource->created_at,
            'shipping_method' => $this->resource->shipping_method->label(),
            'shipping_address' => $this->resource->address ? new UserAddressResource($this->resource->address):'',
            'items' => OrderDetailResource::collection($this->resource->items)
        ];
    }
}
