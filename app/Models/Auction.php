<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    use HasFactory;

    const IS_BESTSELLER = '1';
    const IS_NEW = '1';
    const NUMBER_OF_ITEMS_PER_PAGE = 12;
    const NUMBER_OF_BESTSELLERS_ITEMS_PER_PAGE = 4;
    const NUMBER_OF_NEW_ITEMS_PER_PAGE = 4;
    const RECENT_VIEW_LIMIT = 4;

    protected $fillable = [
        'basket_id',
        'title',
        'description',
        'is_private',
        'auction_type',
        'max_price',
        'invitations',
    ];

//    public function bids() :HasMany
//    {
//        $this->hasMany(AuctionBid::class);
//    }

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

    public function reviews()
    {
        return $this->morphMany(Review::class,'reviewable');
    }

    public function items()
    {
        return $this->morphMany(OrderDetail::class,'orderable');
    }
}
