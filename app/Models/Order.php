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
 * @property UserAddress $address_id
 */
class Order extends Model
{
    use HasFactory;

    protected $guarded=['id'];
    const AUCTION_PACK_TYPE = 1;
    const AUCTION_TYPE = 2;
    const PRODUCT_TYPE = 3;

    public function wine_user()
    {
        return $this->belongsTo(WineUser::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function items()
    {
        return $this->hasMany(OrderDetail::class);
    }

}
