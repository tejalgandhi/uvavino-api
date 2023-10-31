<?php

namespace App\Models;

use App\Contracts\ConnectableUser;
use App\Models\Traits\FileUploadTrait;
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
    use FileUploadTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded=['id'];

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
       if($this->available_auctions > 0){
           return true;
       }
        return false;
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
        return $this->id;
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
    public function countRatingGroupedBy()
    {
        return $this->reviews()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
