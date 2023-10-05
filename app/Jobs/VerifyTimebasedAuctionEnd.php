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

class VerifyTimebasedAuctionEnd implements ShouldQueue
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
        $today = Carbon::now();
        $end_date = Carbon::createFromFormat('Y-m-d H:s:i', $this->auction->end_date);
        if($end_date <= $today && $this->auction->is_ended == Auction::IS_NOT_ENDED_AUCTION)
        {
            $bid = AuctionBid::where('auction_id',$this->auction->id)->latest()->first();
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
}
