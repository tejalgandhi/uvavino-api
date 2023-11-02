<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
  protected $guarded = ['id'];

  public static function getCustomer($userId) {
    return self::select('id', 'stripe_customer_id')->whereUserId($userId)->first();
}

}
