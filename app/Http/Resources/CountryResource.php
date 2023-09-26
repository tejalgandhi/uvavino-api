<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'name' => $this->resource->name,
            'iso_alpha_2' => $this->resource->iso_alpha_2,
            'iso_alpha_3' => $this->resource->iso_alpha_3,
            'flag' => country_flag($this->resource->iso_alpha_2),
        ];
    }
}
