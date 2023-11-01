<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AuctionEnded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $auction;
    public $current_bid;
    public $next_bid;
    public $quick_bids;

    public function __construct($auction)
    {
        $this->auction = $auction->id;
        $this->current_bid = $auction->bidRaised();
        $this->next_bid = $auction->nextMinBid();
        $this->quick_bids = $auction->quickBid();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::info('BidRaised event broadcasted');

        return new Channel('auction.'.$this->auction);
    }

    public function broadcastWith()
    {
        Log::info('Broadcasting auction data', [
            'current_bid' => $this->current_bid,
            'next_bid' => $this->next_bid,
            'quick_bids' => $this->quick_bids,
        ]);

        return [
            'current_bid' => $this->current_bid,
            'next_bid' => $this->next_bid,
            'quick_bids' => $this->quick_bids,
        ];
    }

}
