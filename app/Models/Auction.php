<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use App\Enums\AuctionType;

class Auction extends Model
{
    use HasFactory;

    const IS_BESTSELLER = '1';
    const IS_NEW = '1';
    const NUMBER_OF_ITEMS_PER_PAGE = 12;
    const NUMBER_OF_BESTSELLERS_ITEMS_PER_PAGE = 4;
    const NUMBER_OF_NEW_ITEMS_PER_PAGE = 4;
    const RECENT_VIEW_LIMIT = 4;
    public $timestamps = false;

    protected $guarded = ['id'];


    public static function boot() {
        parent::boot();

        // create a event to happen on deleting
        static::deleting(function($table)  {
            $table->deleted_by = Auth::user()->id ?? NULL;
        });

        // create a event to happen on saving
        static::saving(function($table)  {
            $table->created_by = Auth::user()->id ?? NULL;
        });
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class,Basket::class ,'id','basket_id');
    }

    public function countryId() : Attribute
    {
        return Attribute::make(
            fn () => $this->products()?->first()?->country_id,
        );
    }
    public function countryName() : Attribute
    {
        return Attribute::make(
            fn () => $this->products()?->first()?->country?->name,
        );
    }

    public function wishlist(){
        return $this->hasMany(Wishlist::class,'ref_id','id')->where('type',Wishlist::AUCTION_TYPE);
    }

    public function inWishlist() : Attribute
    {
        return Attribute::make(get: fn() => $this->withCount([
            'wishlist' => fn($q) =>
            $q->where('wine_user_id', request()->user_id)])->first()->wishlist_count > 0);
    }

    public function bids()
    {
        return  $this->hasMany(AuctionBid::class);
    }


    public function baskets(): BelongsTo
    {
        return $this->belongsTo(Basket::class,'basket_id','id');
    }

    public function canBid(int $amt):bool
    {
        // Need to write correct logic
        return true;
    }

    public function nextMinBid()
    {
        // Need to write correct logic
        return 0;
    }
    public static function getBestSellersProduct()
    {
        $query = self::where('bestsellers', Auction::IS_BESTSELLER)->orderBy('bestsellers_order', 'ASC');
        return $query->paginate(self::NUMBER_OF_BESTSELLERS_ITEMS_PER_PAGE);
    }

    public static function getNewProduct()
    {
        $query = self::where('new_release', Auction::IS_NEW)->orderBy('new_release_order', 'ASC');
        return $query->paginate(self::NUMBER_OF_NEW_ITEMS_PER_PAGE);
    }
}
