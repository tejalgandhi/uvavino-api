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
    public $previous_bid;
    public $previous_bid_user;
    public $last_bid_at;

    public function __construct($auction)
    {
        $this->auction = $auction->id;
        $this->last_bid_at = $auction->last_bid_at;
        $this->current_bid = $auction->bidRaised();
        $this->next_bid = $auction->nextMinBid();
        $this->quick_bids = $auction->quickBid();
        $this->previous_bid = $auction->secondLastBid()?->value;
        $this->previous_bid_user = $auction->secondLastBid()?->wine_user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::info('AuctionEnded event broadcasted');

        return new Channel('auction.'.$this->auction);
    }

    public function broadcastWith()
    {
        Log::info('Broadcasting AuctionEnded data', [
            'current_bid' => $this->current_bid,
            'next_bid' => $this->next_bid,
            'quick_bids' => $this->quick_bids,
            'previous_bid' => $this->previous_bid,
            'previous_bid_user' => $this->previous_bid_user,
            'last_bid_at' => $this->last_bid_at,
        ]);

        return [
            'current_bid' => $this->current_bid,
            'next_bid' => $this->next_bid,
            'quick_bids' => $this->quick_bids,
            'previous_bid' => $this->previous_bid,
            'previous_bid_user' => $this->previous_bid_user,
            'last_bid_at' => $this->last_bid_at,
        ];
    }

}
