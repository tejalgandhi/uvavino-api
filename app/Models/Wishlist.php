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

    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class,'basket_id','id');
    }

}
