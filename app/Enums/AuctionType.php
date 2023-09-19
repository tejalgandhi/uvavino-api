<?php
namespace App\Enums;
use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

Enum AuctionType: int {
    use InvokableCases;
    use Values;

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


