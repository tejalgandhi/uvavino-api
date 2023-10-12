<?php

namespace App\Models;

use App\Http\Resources\AuctionPackResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderType;


class CartDetail extends Model
{
    use HasFactory;


    protected $fillable = [
        'cartable_type',
        'cartable_id',
        'total_amount',
        'amount',
        'qty',
    ];

    public function cartable()
    {
        return $this->morphTo();
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }



}
