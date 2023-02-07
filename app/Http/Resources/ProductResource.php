<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'wine_name' => $this->resource->wine_name,
            'price' => $this->resource->packaging?->price,
            'description' => $this->resource->description,
            'wine_user' => $this->resource->wine_user->username,
            'drink_type' => $this->resource->drink_type->name,
            'bottle_size' => $this->resource->bottle_size,
            'country_name' => $this->resource->country->name,
            'region_name' => $this->resource->region->name,
            'producer_name' => $this->resource->producer->name,
            'wine_maker' => $this->resource->wine_maker,
            'wine_type' => $this->resource->wine_type->name,
            'year' => $this->resource->year,
            'grape_varieties' => $this->resource->grape_varieties,
            'wine_tag' => $this->resource->wine_tag->name,
            'storage_condition' => $this->resource->storage_condition,
            'review_and_rewards' => $this->resource->review_and_rewards,
            'medal' => $this->resource->medal,
            'award_name' => $this->resource->award_name,
            'score' => $this->resource->score,
            'review_name' => $this->resource->review_name,
        ];
    }

    public static function selectLst() {
        return [
            'products.id',
            'products.wine_name',
            'packaging.price',
            'products.description',
            'wine_users.username AS wine_user',
            'drink_types.name AS drink_type',
            'products.bottle_size',
            'countries.name AS country_name',
            'regions.name AS region_name',
            'producers.name AS producer_name',
            'products.wine_maker',
            'wine_types.name AS wine_type',
            'products.year',
            'products.grape_varieties',
            'wine_tags.name AS wine_tag',
            'products.storage_condition',
            'products.review_and_rewards',
            'products.medal',
            'products.award_name',
            'products.score',
            'products.review_name',
        ];
    }
}
