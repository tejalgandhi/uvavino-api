<?php

namespace App\Models;

use App\Contracts\ConnectableUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Immera\Payment\Contracts\PaymentUserContract;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\WineUser
 *
 * @property mixed $id
 * @property string $name
 * @property mixed $wishlist
 */
class WineUser extends Authenticatable implements ConnectableUser, PaymentUserContract
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function auctionPacks()
    {
        return $this->hasMany(AuctionPackage::class);
    }

    public function canAuction(): bool
    {
        // get purchased auctions
        // $max = $this->auctionPacks()->sum('qty');
        // $present = $this->orders()->with('order')
        return true;
    }

    public function updateConnectId($id): void {
        $this->connection_id = $id;
        $this->save();
        $this->refresh();
    }

    public function getConnectId(): string {
        return $this->connection_id;
    }

    public function getEmail(): string {
        return $this->email;
    }
  
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // This will be used in the payment to get stripe ID
    public function getId()
    {
        return $this->stripe_customer_id;
    }

    public function getName()
    {
        return $this->first_name && $this->last_name ?
            implode(" ", [$this->first_name, $this->last_name]):
            $this->username;
    }

    public function wishlist() {
        return $this->belongsToMany(Product::class, 'wishlist', 'wine_user_id', 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
