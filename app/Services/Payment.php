<?php

namespace App\Services;

use App\Contracts\ConnectableUser as User;
use Stripe\StripeClient;

class Payment {

  protected StripeClient $stripe;

  public function __construct() {
    $this->stripe = app('stripe');
  }

  public function connect(User $user) {
    if (!$user->getConnectId()) {
      $account = $this->stripe->accounts->create([
        'country' => 'PT',
        'type' => 'custom',
        'capabilities' => [
          'card_payments' => ['requested' => true],
          'transfers' => ['requested' => true],
        ],
        'email' => $user->getEmail(),
        'settings' => ['payouts' => ['schedule' => ['interval' => 'manual']]],
      ]);
      if ($account) {
        $user->updateConnectId($account->id);
      }
    }
    $accountInfo =$this->stripe->accounts->retrieve($user->getConnectId());
    if (!($accountInfo && $accountInfo->charges_enabled)) {
      $front_url = config('app.front_app_url');
      $account_links = $this->stripe->accountLinks->create([
        'account' => $user->getConnectId(),
        'refresh_url' => $front_url . '/seller/onboard/refresh',
        'return_url' => $front_url . '/seller/onboard/return',
        'type' => 'account_onboarding',
        'collect' => 'eventually_due',
      ]);
      if ($account_links) {
        return $account_links->url;
      }
    }
  }

  public function pay() {
    // Need to write appropreate code here.
  }
}