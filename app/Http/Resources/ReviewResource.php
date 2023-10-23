<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\ReviewType;


class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $productType = ReviewType::product->className();
        $wineuserType = ReviewType::wine_user->className();
        if ($this->resource->reviewable_type == $productType) {
            $reviewable = new BasketResource($this->resource->reviewable);
        }
        else if ($this->resource->reviewable_type == $wineuserType) {
            $reviewable = new WineUserResource($this->resource->reviewable);
        }else {
            $reviewable = new AuctionPackResource($this->resource->reviewable);
        }

        return [
            'id' => $this->resource->id,
            'reviewable_type' => $this->resource->reviewable_type,
            'reviewable_id' => $this->resource->reviewable_id,
            'wine_user_id' => $this->resource->wine_user_id,
            'user_name' => $this->resource->wineUser->username,
            'rating' => $this->resource->rating,
            'review_content' => $this->resource->review_content,
            'review_date' => $this->resource->created_at,
            'reviewable' => $reviewable

        ];
    }
}
