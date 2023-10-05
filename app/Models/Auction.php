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

    public $timestamps = false;

    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at', 'start_date','end_date'];

    const IS_ENDED_AUCTION = '1';
    const IS_NOT_ENDED_AUCTION = '0';

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

    public function scopePrivateAuctions($query)
    {
        return $query->where('is_private','1');
    }
    /**
     * Scope a query to only get inprogress auction.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnfinishedAuctions($query)
    {
        return $query
            ->where('is_private','0')
            ->where('is_publish','1')
            ->where(function ($q) {
                $q->where('type', AuctionType::TIMEBASED->value)
                    ->where('end_date', '>=', now())
                    ->orWhere('is_ended',Auction::IS_NOT_ENDED_AUCTION);
            })
            ->orWhere(function ($q) {
                $q->where('type', AuctionType::LIVE->value)
                        ->orWhere('is_ended',Auction::IS_NOT_ENDED_AUCTION);
            });
    }
    /**
     * Scope a query to  get bid count and wishlist auction.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBidCounts($query){
        return  $query->withCount(['bids','wishlist'=> function($q)   {
            $q->where('wine_user_id',request()->user_id);
        }
        ]);
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
    public function countryIsoCode() : Attribute
    {
        return Attribute::make(
            fn () => $this->products()->first()?->country?->iso_alpha_2,
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
    public function bidRaised() : int
    {
        // Need to write correct logic
        $auction_bid = AuctionBid::where('auction_id',$this->id)->latest()->first();

        return !empty($auction_bid) ? $auction_bid->value : 0 ;
    }
    public function nextMinBid() : int
    {
        // Need to write correct logic
        $auction_bid = AuctionBid::where('auction_id',$this->id)->latest()->first();
        if(empty($auction_bid)){
            $latest_bid = $this->baskets ? $this->baskets->price:4;
        } else{
            $latest_bid = $auction_bid->value ?? 4;
        }
        return $latest_bid + 1;
    }

    public function quickBid() : array
    {
        // Need to write correct logic
        $latest_bid = $this->nextMinBid();
        $bids = [
            $latest_bid,
            $latest_bid + 1,
            $latest_bid + 2,
        ];

        return $bids ;
    }
    public static function getBestSellersProduct()
    {
        $query = self::bidCounts()->unfinishedAuctions()
            ->orderBy('bids_count', 'desc');
        return $query->paginate(Config('constant.new_items_per_page'));
    }

    public static function getNewProduct()
    {
        $query = self::bidCounts()->unfinishedAuctions()
            ->orderbyDesc('id');
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
    public function userLatestBid($user_id = 0)
    {
        return AuctionBid::where('wine_user_id',$user_id)->where('auction_id',$this->id)->latest()->first();
    }
}
