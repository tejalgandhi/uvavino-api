<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Enums\AuctionType;
use Illuminate\Support\Facades\Log;

class Auction extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $guarded = ['id'];
    protected $dates = ['start_date','end_date'];

    const IS_ENDED_AUCTION = '1';
    const IS_NOT_ENDED_AUCTION = '0';

    public $casts =[
      'type'=>AuctionType::class,
    ];


    public static function boot() {
        parent::boot();

        // create a event to happen on deleting
        static::deleting(function($model)  {
            // Check if the auction has not started
            $currentTime = Carbon::now();
            $startTime = Carbon::parse($model->start_date);
            if($currentTime < $startTime) {
                $available_auctions = Auth::user()->available_auctions + 1;
                WineUser::where('id',Auth::user()->id)->update(['available_auctions'=>$available_auctions]);
            }
            $model->deleted_by = Auth::user()->id ?? NULL;
            $model->save();
        });

        // create a event to happen on saving
        static::saving(function($model)  {
            $model->created_by = Auth::user()->id ?? NULL;
        });
        // create a event to happen on saving
        static::created(function($model)  {
            $available_auctions = Auth::user()->available_auctions - 1;
            WineUser::where('id',Auth::user()->id)->update(['available_auctions'=>$available_auctions]);
        });
    }

    public function scopePrivateAuctions($query)
    {
        return $query->where('is_private','1')->where('created_by',Auth::user()->id);
    }
    public function scopePublishAuctions($query)
    {
        return $query->where('is_private','0')
            ->where('is_publish','1');
    }
    public function scopeFinishedAuctions($query)
    {
        return $query->where('is_ended',Auction::IS_ENDED_AUCTION);
    }
    public function scopeParticipateAuctions($query)
    {
        return $query->whereHas('bids',function ($q){
            $q->where('wine_user_id',request()->wine_user_id);
        });
    }
    public function scopeInviteAuctions($query)
    {
        return $query->whereHas('invites',function ($q){
            $q->where('email',Auth::user()->email);
        });
    }
    /**
     * Scope a query to only get inprogress auction.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnfinishedAuctions($query)
    {
        return $query->where('is_private','0')
                    ->where('is_publish','1')
                    ->where(function ($q) {
                        $q->where(function ($q) {
                            $q->where('type', AuctionType::TIMEBASED->value)
                                    ->where('end_date', '>=', now())
                                ->orWhere('is_ended', Auction::IS_NOT_ENDED_AUCTION);
                            })
                            ->orWhere(function ($q) {
                                $q->where('type', AuctionType::LIVE->value)
                                        ->orWhere('is_ended', Auction::IS_NOT_ENDED_AUCTION);
                            });
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
        return $this->hasManyThrough(Product::class, Basket::class, 'id', 'basket_id', 'basket_id', 'id');
    }

//    public function getCountryIdAttribute(){
//        Log::info('country');
//        Log::info($this->products()->first());
//        return $this->products()->first()?->country_id;
//    }

    public function countryId() : Attribute
    {
        Log::info('country');
        $firstProduct = $this->products()->first();
        Log::info($firstProduct);
        return Attribute::make(
            fn () => $firstProduct?->country_id,
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
        return $this->hasMany(Wishlist::class,'wishlistable_id','id')->where('wishlistable_type',Auction::class);
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
        Log::info($auction_bid);

        return !empty($auction_bid) ? $auction_bid->value : 0 ;
    }
    public function nextMinBid() : float
    {
        // Need to write correct logic
        $minimum_bid_step = Config('constant.minimum_bid_step');
        $auction_bid = AuctionBid::where('auction_id',$this->id)->latest()->first();
        if(empty($auction_bid)){
            $latest_bid = $this->baskets ? $this->baskets->price:4;
        } else{
            $latest_bid = $auction_bid->value ?? 4;
        }
        return $latest_bid + $minimum_bid_step;
    }

    public function quickBid() : array
    {
        // Need to write correct logic
        $minimum_bid_step = Config('constant.minimum_bid_step');
        $latest_bid = $this->nextMinBid();
        $bids = [
            $latest_bid,
            $latest_bid + $minimum_bid_step,
            $latest_bid + ($minimum_bid_step*2),
        ];

        return $bids ;
    }

    public function autoBids(): HasMany
    {
        return $this->hasMany(AutomaticBid::class);
    }

    public function activeAutoBid(): HasMany
    {
        $highestBid = $this->bids()->max('value') ?? $this->baskets->price;
        return $this->autoBids()->where('max_limit','>=',$highestBid);
    }

    public function makeAutoBid(AutomaticBid $automaticBid): bool
    {

        Log::info("trying to autobid from {".$automaticBid->wine_user_id."}");
        $min = $this->nextMinBid();
        $latestBid = $this->bids()->latest()->first();
             Log::info('min ' . $min);
             if ($automaticBid->max_limit >= $min) {
                 Log::info($automaticBid->wine_user_id . " bidded automatically.");
                 Log::info('$min');
                 Log::info($min);
                 Log::info('$latestBid');
                 Log::info($latestBid);
                 if((empty($latestBid) || (!empty($latestBid) && $latestBid->wine_user_id != $automaticBid->wine_user_id && $min != $latestBid->value))) {
                     $this->setNewBid($automaticBid->wine_user_id, $min);
                 }
                 return true;
             }
        Log::info($automaticBid->wine_user_id ." can't bid automatically (out of limit).");
        return false;

    }

    public function setNewBid($user_id,$min)
    {
        AuctionBid::create([
            'auction_id' => $this->id,
            'wine_user_id' => $user_id,
            'value' => $min
        ]);

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
    public function userLatestInvite($user_id = 0)
    {
        $user = WineUser::where('id',$user_id)->first();
        return AuctionInvite::where('email',$user->email)->where('auction_id',$this->id)->latest()->first();
    }
    public function userLatestBid($user_id = 0)
    {
        \Log::info('userLatestBid user: ' . $user_id . ' auction: ' .  $this->id);
        return $this->bids()->where('wine_user_id',$user_id)->latest()->first();
    }
    public function userAuctionStatus($user_latest_time = 0)
    {
        if($this->is_ended == Auction::IS_NOT_ENDED_AUCTION){
            return '';
        }

        return !empty($user_latest_time) && $user_latest_time->id == $this->winner_bid?'Won':'Lose';
    }
}
