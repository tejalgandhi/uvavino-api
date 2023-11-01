<?php

namespace App\Events;

use App\Models\Auction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

/**
 * Class BidRaised
 * @package App\Events
 */
class BidRaised implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $timeout = 3600;
    public $auction;
    public $current_bid;
    public $next_bid;
    public $quick_bids;
    public $bid_user;
    public $previous_bid;
    public $previous_bid_user;

    public function __construct($auction,$bid_user)
    {
            $this->auction = $auction->id;
            $this->current_bid = $auction->bidRaised();
            $this->next_bid = $auction->nextMinBid();
            $this->quick_bids = $auction->quickBid();
            $this->bid_user = $bid_user;
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
        Log::info('BidRaised event broadcasted');

        return new Channel('auction.'.$this->auction);
    }

    public function broadcastWith()
    {
        Log::info('Broadcasting auction data', [
            'auction' => $this->auction,
            'current_bid' => $this->current_bid,
            'next_bid' => $this->next_bid,
            'quick_bids' => $this->quick_bids,
            'bid_user' => $this->bid_user,
            'previous_bid' => $this->previous_bid,
            'previous_bid_user' => $this->previous_bid_user,
        ]);

        return [
            'auction' => $this->auction,
            'current_bid' => $this->current_bid,
            'next_bid' => $this->next_bid,
            'quick_bids' => $this->quick_bids,
            'bid_user' => $this->bid_user,
            'previous_bid' => $this->previous_bid,
            'previous_bid_user' => $this->previous_bid_user,
        ];
    }
}
