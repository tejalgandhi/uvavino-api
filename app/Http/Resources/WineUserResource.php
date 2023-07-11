<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\OrderableType;

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
        $total_auction_packs = $this->orders()
            ->where('type',Order::AUCTION_PACK_TYPE)
            ->withCount(['items as itemQuantity' => function($query) {
                            $query->select(\DB::raw('SUM(qty)'));
                }])
            ->get()->sum('itemQuantity');

        $total_auctions = $this->orders()
            ->where('type',Order::AUCTION_TYPE)
            ->withCount(['items as itemQuantity' => function($query) {
                $query->select(\DB::raw('SUM(qty)'));
            }])
            ->get()->sum('itemQuantity');

        return [
            'id' => $this->id,
            'username' => $this->username,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name  ?? '',
            'email' => $this->email,
            'stripe_customer_id' => $this->stripe_customer_id  ?? '',
            'connection_id' => $this->connection_id  ?? '',
            'remaining_auction_qty' => $total_auction_packs-$total_auctions,
        ];
    }
}
