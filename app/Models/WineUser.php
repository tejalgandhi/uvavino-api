<?php

namespace App\Models;

use App\Contracts\ConnectableUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Immera\Payment\Contracts\PaymentUserContract;
use Laravel\Sanctum\HasApiTokens;
/**
 * @codeCoverageIgnore
 */
/**
 * App\Models\WineUser
 *
 * @property mixed $id
 * @property string $name
 * @property string $slug
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

//    /**
//     * @return HasOne
//     */
//    public function auctionPacks() : HasOne
//    {
//        return $this->hasOne(AuctionPackage::class,'wine_user_id','id');
//    }

    public function canAuction(): bool
    {
        $total_auction_packs = $this->orders()
            ->where('type',Order::AUCTION_PACK_TYPE)
            ->withCount(['items as itemQuantity' => function($query) {
                $query->select(\DB::raw('SUM(qty)'));
            }])
            ->get()->sum('itemQuantity');

        $total_auctions = $this->orders()
            ->where('type',Order::AUCTION_TYPE)
            ->withCount(['items as itemQuantity' => function($query) {
                $query->select(\DB::raw('SUM(qty)'));
            }])
            ->get()->sum('itemQuantity');
        // get purchased auctions
        // $max = $this->auctionPacks()->sum('qty');
        // $present = $this->orders()->with('order')
//        return ($total_auction_packs -  $total_auctions > 0) ? true : false;
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
    public function getId() : string
    {
        return $this->stripe_customer_id;
    }

    public function getName()
    {
        return $this->username;
    }

    public function wishlist() : HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class,'reviewable');
    }
    public function averageRating()
    {
        return number_format($this->reviews()->avg('rating'), 1);
    }
    public function countRating()
    {
        return $this->reviews()->count('rating');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
