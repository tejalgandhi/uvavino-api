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
    protected $casts = [
        'payment_response' => 'array',
    ];

    // Payment methods
    const CASH = 'cash';
    const ALI_PAY = 'Ali Pay';
    const WECHAT_PAY = 'Wechat Pay';
    const MULTIBANCO = 'Multibanco';
    const BANK_TRANSFER = 'Bank transfer';
    const PAYPAL = 'PayPal';
    const CARD = 'Card';

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

    public static function getMethodFromIndex($index)
    {
        $methods = [
            static::ALI_PAY,
            static::WECHAT_PAY,
            static::MULTIBANCO,
            static::CASH,
            static::PAYPAL,
        ];

        return $index < count($methods) && $index >= 0 ? $methods[$index] : static::CASH;
    }
    public function setPaymentMethodFromIndex($num = null, $payment_method_field = 'payment_method')
    {
        if ($num) {
            $index = ((int) $num) - 1;
            $this->$payment_method_field = static::getMethodFromIndex($index);
        } else {
            $this->$payment_method_field = static::CASH;
        }
    }

    public function items()
    {
        return $this->hasMany(OrderDetail::class);
    }

}
