<?php
namespace App\Enums;

Enum AuctionType: int {
    case LIVE = 0;
    case TIMEBASED = 1;

    public function label()
    {
        return match($this) {
            self::LIVE => 'Live',
            self::TIMEBASED => 'Timebased'
        };
    }
    public function value()
    {
        return match($this) {
        self::LIVE => 0,
        self::TIMEBASED => 0,

        };
    }
}


