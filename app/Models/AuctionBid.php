<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuctionBid extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function wine_user(): BelongsTo
    {
        return $this->belongsTo(WineUser::class,'wine_user_id','id');
    }
}
