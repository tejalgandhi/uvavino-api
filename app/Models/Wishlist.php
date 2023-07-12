<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = 'wishlist';

    protected $guarded = ['id'];

    const AUCTION_TYPE = 2;
    const PRODUCT_TYPE = 3;

    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class,'ref_id','id');
    }

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class,'ref_id','id');
    }

}
