<?php

namespace App\Models;

use App\Jobs\VerifyLiveAuctionEnd;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\AuctionType;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Support\Facades\Log;

class AuctionBid extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function boot() {
        parent::boot();


        static::created(function($auctionBid)  {

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

    public function storeEvent(AuctionBid $auctionBid){
        $bid_user = $auctionBid->wine_user->id;
        event(new  \App\Events\BidRaised($auctionBid->auction,$bid_user));
        if ($auctionBid->auction->type == AuctionType::LIVE) {
            Log::info('config constant');
            Log::info(config('constant.configured_auction_time'));
            Log::info($auctionBid);
            Log::info('$auctionBid->created_at');
            Log::info($auctionBid->created_at);
            $time = $auctionBid->auction->live_time_interval ?? config('constant.configured_auction_time');
            dispatch(new VerifyLiveAuctionEnd($auctionBid->auction))->delay($auctionBid->created_at->addSeconds($time));
        }
    }
}
