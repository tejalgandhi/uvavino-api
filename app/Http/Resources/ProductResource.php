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
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'wine_name' => $this->resource->wine_name,
            'price' => $this->resource->price,
            'description' => $this->resource->description,
            'wine_user_id' => $this->resource->wine_user_id,
            'wine_user' => $this->resource->wine_user->username,
            'drink_type_id' => $this->resource->drink_type_id,
            'drink_type' => $this->resource->drink_type->name,
            'bottle_size' => $this->resource->bottle_size,
            'country_id' => $this->resource->country_id,
            'country_name' => $this->resource->country->name,
            'region_id' => $this->resource->region_id,
            'region_name' => $this->resource->region->name,
            'producer_id' => $this->resource->producer_id,
            'producer_name' => $this->resource->producer->name,
            'wine_maker' => $this->resource->wine_maker,
            'wine_type_id' => $this->resource->type_of_wine,
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

    public static function selectLst()
    {
        return [
            'products.id',
            'products.wine_name',
            'products.description',
            'products.bottle_size',
            'producers.name AS producer_name',
            'products.wine_maker',
            'products.year',
            'products.grape_varieties',
            'products.storage_condition',
            'products.review_and_rewards',
            'products.medal',
            'products.award_name',
            'products.score',
            'products.review_name',
        ];
    }
}
