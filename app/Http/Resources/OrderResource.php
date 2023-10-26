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
            'id' => $this->id,
//            'wine_user' => new WineUserResource($this->wine_user),
            'wine_user' => $this->wine_user_id,
            'order_no' => $this->order_no,
            'request_date' => $this->created_at,
            'shipping_address' => $this->address ? new UserAddressResource($this->address):'',
            'items' => OrderDetailResource::collection($this->items)
        ];
    }
}
