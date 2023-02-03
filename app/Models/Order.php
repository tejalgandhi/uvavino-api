<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property mixed $id
 * @property string $name
 * @property WineUser $wine_user
 */
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
