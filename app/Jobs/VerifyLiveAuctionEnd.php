<?php

namespace App\Jobs;

use App\Models\Auction;
use App\Models\AuctionBid;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerifyLiveAuctionEnd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $auction;

    /**
     * Create a new job instance.
     * @param $auction
     */
    public function __construct($auction)
    {
        $this->auction = $auction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('in live auction start');
        $secs = $this->auction->live_time_interval ?? config('constant.configured_auction_time');
        $configure_time = Carbon::parse($this->auction->start_date)
            ->addSeconds($secs)
            ->format('Y-m-d H:i:s');
           if($this->auction->is_ended == Auction::IS_NOT_ENDED_AUCTION)
           {
               $bid = AuctionBid::where('auction_id',$this->auction->id)->latest()->first();
               $today = $diff = $diff_auction= Carbon::now();
               if(!empty($bid)) {
                   $configure_time = Carbon::parse($bid->created_at)
                       ->addSeconds($secs)
                       ->format('Y-m-d H:i:s');
               }

               if((empty($bid) && $today->greaterThanOrEqualTo($configure_time))|| ($today->greaterThanOrEqualTo($configure_time)))
               {
                   $winner_bid = !empty($bid)?$bid->id:NULL;
                    Auction::where('id',$this->auction->id)->update([
                        'is_ended'=>Auction::IS_ENDED_AUCTION,
                        'winner_bid'=>$winner_bid
                    ]);

                   $current_bid = $this->auction->bidRaised();
                   $next_bid = $this->auction->nextMinBid();
                   $quick_bids = $this->auction->quickBid();
                   event(new  \App\Events\AuctionEnded($this->auction->id, $current_bid, $next_bid, $quick_bids));
               }
           }
        Log::info('in live auction end');
    }
}
