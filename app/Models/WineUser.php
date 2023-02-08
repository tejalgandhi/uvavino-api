<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class WineUser extends Authenticatable
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

    public function orders()
    {
        // return $this->hasMany(Order::class);
    }

    public function canAuction(): bool
    {
        // get purchased auctions
        // $max = $this->auctionPacks()->sum('qty');
        // $present = $this->orders()->with('order')
        return true;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
