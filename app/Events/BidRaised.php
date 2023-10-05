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
class BidRaised implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $timeout = 3600;
    public $auction;
    public $current_bid;
    public $next_bid;
    public $quick_bids;

    public function __construct($auction,$current_bid,$next_bid,$quick_bids)
    {
            $this->auction = $auction;
            $this->current_bid = $current_bid;
            $this->next_bid = $next_bid;
            $this->quick_bids = $quick_bids;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::info('BidRaised event broadcasted');

        return new Channel('auction'.$this->auction);
    }

    public function broadcastWith()
    {
        Log::info('Broadcasting auction data', [
            'auction' => $this->auction,      
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
