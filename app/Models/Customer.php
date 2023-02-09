<?php

namespace App\Models;

class Customer
{

  public $stripe_customer_id = NULL;

  public function __construct($sid = NULL)
  {
    $this->stripe_customer_id = $sid;    
  }

  public static function create($data)
  {
    $user = WineUser::find($data['user_id']);
    if ($user) {
      $user->stripe_customer_id = $data['stripe_customer_id'];
      $user->save();
      $user->refresh();
      return (new Customer($user->stripe_customer_id));
    }
    else return null;
  }

  public static function getCustomer($id)
  {
    $user = WineUser::whereStripeCustomerId($id)->first();
    if ($user) return (new Customer($user->stripe_customer_id));
    else return null;
  }

}
