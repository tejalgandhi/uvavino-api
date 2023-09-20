<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Enums\AuctionType;
use Illuminate\Support\Facades\Config;

class Auction extends Model
{
    use HasFactory;
    use SoftDeletes;

    const IS_BESTSELLER = '1';
    const IS_NEW = '1';
    const NUMBER_OF_ITEMS_PER_PAGE = 12;
    const NUMBER_OF_BESTSELLERS_ITEMS_PER_PAGE = 4;
    const NUMBER_OF_NEW_ITEMS_PER_PAGE = 4;
    const RECENT_VIEW_LIMIT = 4;
    public $timestamps = false;

    protected $guarded = ['id'];

    public $casts =[
      'type'=>AuctionType::class,
    ];


    public static function boot() {
        parent::boot();

        // create a event to happen on deleting
        static::deleting(function($model)  {
            $model->deleted_by = Auth::user()->id ?? NULL;
            $model->save();
        });

        // create a event to happen on saving
        static::saving(function($model)  {
            $model->created_by = Auth::user()->id ?? NULL;
        });
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class,Basket::class ,'id','basket_id');
    }

    public function countryId() : Attribute
    {
        return Attribute::make(
            fn () => $this->products()->first()?->country_id,
        );
    }
    public function countryName() : Attribute
    {
        return Attribute::make(
            fn () => $this->products()->first()?->country?->name,
        );
    }

    public function wishlist() :HasMany
    {
        return $this->hasMany(Wishlist::class,'ref_id','id')->where('type',Wishlist::AUCTION_TYPE);
    }

    public function bids() :HasMany
    {
        return  $this->hasMany(AuctionBid::class);
    }

    public function winnerBid() :BelongsTo
    {
        return  $this->belongsTo(AuctionBid::class,'winner_bid','id');
    }

    public function invites() :HasMany
    {
        return  $this->hasMany(AuctionInvite::class);
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
        $query = self::withCount('bids') ->withCount(['bids','wishlist'=> function($q)   {
            $q->where('wine_user_id',request()->user_id);
        } ])->orderBy('bids_count', 'desc');
        return $query->take(Config('constant.recommended_items_per_page'))->get();
    }

    public static function getNewProduct()
    {
        $query = self::where('is_publish',1) ->withCount(['bids','wishlist'=> function($q)   {
            $q->where('wine_user_id',request()->user_id);
        } ])->orderbyDesc('id');
        return $query->paginate(Config('constant.new_items_per_page'));
    }

    public function reviews()
    {
        return $this->morphMany(Review::class,'reviewable');
    }
    public function rating_order_count()
    {
        return $this->reviews()
            ->selectRaw('count(rating) as rating_count, rating')
            ->groupBy('rating');

    }
    public function averageRating()
    {
        return number_format($this->reviews()->avg('rating'), 1);
    }
    public function countRating()
    {
        return $this->reviews()->count('rating');
    }

//    public function items()
//    {
//        return $this->morphMany(OrderDetail::class,'orderable');
//    }

    public function wine_user(): BelongsTo
    {
        return $this->belongsTo(WineUser::class,'created_by','id');
    }
}
