<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function wine_user()
    {
        return $this->belongsTo(WineUser::class);
    }

    public function items()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
