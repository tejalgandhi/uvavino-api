<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ProductAttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $result = [];
        if(in_array("*", $request->list_attrs)) {
            return parent::toArray($request);
        }
        foreach($request->list_attrs as $indx => $attr) {
            if(is_numeric($indx)) {
                $key = implode("_", explode(".", $attr));
                $result[$key] = Arr::get($this, $attr);
            } else {
                $result[$attr] = Arr::get($this, $indx);
            }
        }
        return $result;
    }
}
