<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
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
            'wine_user_id' => $this->wine_user_id,
            'nif' => $this->nif  ?? '',
            'country_code' => $this->country_code,
            'country' => $this->country  ?? '',
            'address_first_line' => $this->address_first_line  ?? '',
            'address_second_line' => $this->address_second_line??'',
            'city' => $this->city ?? '',
            'state' => $this->state ?? '',
            'post_code' => $this->post_code ?? '',
            'phone_no' => $this->phone_no ?? '',
            'email' => $this->email ?? '',
            'dial_code' => $this->dial_code ?? '',
            'iso_alpha2' => $this->iso_alpha2 ?? '',
            'is_default' => $this->is_default ?? 0,
        ];
    }
}
