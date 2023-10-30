<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class WineUserResource extends JsonResource
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
            'username' => $this->username,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name  ?? '',
            'email' => $this->email,
            'stripe_customer_id' => $this->stripe_customer_id  ?? '',
            'connection_id' => $this->connection_id  ?? '',
            'remaining_auction_qty' => $this->available_auctions,
            'city' => $this->city ?? '',
            'country' => $this->country ?? '',
            'image' => $this->image ? json_decode($this->image) : '',
            'rating' => $this->averageRating(),
            'review_count' => $this->countRating(),
            'rating_group_by_count' => $this->countRatingGroupedBy(),
            'about_us' => $this->about_us ?? '',
            'user_created' => $this->created_at ?? '',
        ];
    }
}
