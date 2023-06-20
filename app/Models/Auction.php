<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_type',
        'title',
        'description',
        'is_private',
        'auction_type',
        'max_price',
        'invitations',
    ];

    public function bids()
    {
        $this->hasMany(AuctionBid::class);
    }

    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class,'basket_id','id');
    }

    public function canBid(int $amt):bool
    {
        // Need to write correct logic
        return true;
    }

    public function nextMinBid()
    {
        // Need to write correct logic
        return 0;
    }
}
