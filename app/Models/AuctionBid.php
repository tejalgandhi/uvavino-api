<?php

namespace App\Models;

use App\Jobs\VerifyLiveAuctionEnd;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\AuctionType;

class AuctionBid extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function boot() {
        parent::boot();


        // create a event to happen on saving
        // static::saving(function($model)  {
        //     $current_bid = $model->auction->bidRaised();
        //     $next_bid = $model->auction->nextMinBid();
        //     $quick_bids = $model->auction->quickBid();
        //     event(new  \App\Events\BidRaised($model->auction_id, $current_bid, $next_bid, $quick_bids));
        //     if($model->auction->type == AuctionType::LIVE)
        //     {
        //         dispatch(new VerifyLiveAuctionEnd($model->auction))->delay($model->timestamp->addSeconds(config('constant.configured_auction_time')));
        //     }
        // });

        static::created(function($model)  {
            $current_bid = $model->auction->bidRaised();
            $next_bid = $model->auction->nextMinBid();
            $quick_bids = $model->auction->quickBid();
            $bid_user =$model->wine_user->id;
            event(new  \App\Events\BidRaised($model->auction_id, $current_bid, $next_bid, $quick_bids, $bid_user));
            if($model->auction->type == AuctionType::LIVE)
            {
                dispatch(new VerifyLiveAuctionEnd($model->auction))->delay($model->timestamp->addSeconds(config('constant.configured_auction_time')));
            }
        });
    }

    public function wine_user(): BelongsTo
    {
        return $this->belongsTo(WineUser::class,'wine_user_id','id');
    }

    public function auction() :BelongsTo
    {
        return  $this->belongsTo(Auction::class,'auction_id','id');
    }
}
