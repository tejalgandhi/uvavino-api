<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

Enum AuctionType: int {
    use InvokableCases;
    use Values;

case LIVE = 0;
case TIMEBASED = 1;

    public function label(): string
    {
        return match($this){
        self::LIVE => 'Live',
            self::TIMEBASED => 'Timebased'
        };
    }

}


