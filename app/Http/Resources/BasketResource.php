<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasketResource extends JsonResource
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
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'stock' => $this->resource->stock,
            'price' => $this->resource->price,
            'weight' => $this->resource->weight,
            'dimensions' => $this->resource->dimensions,
            'products' => ProductResource::collection($this->resource->products),
        ];
    }

    public static function selectLst() {
        return [
            'baskets.id',
            'baskets.title',
            'baskets.slug',
            'baskets.description',
            'baskets.stock',
            'baskets.price',
            'baskets.weight',
            'baskets.dimensions',
        ];
    }
}
