<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
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
            'reviews_count' => $this->countRating(),
            'ratings' => $this->averageRating(),
            'rating_order' => $this->rating_order_count,
            'reviews' => ReviewResource::collection($this->reviews),
        ];
    }
}
