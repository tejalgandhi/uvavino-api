<?php
namespace App\Enums;

use ArchTech\Enums\Values;

Enum Medal: int {
    use Values;


case PLATINUM = 0;
    case GOLD = 1;
    case SILVER = 2;

    public function label()
    {
        return match($this) {
            self::PLATINUM => 'Platinum',
            self::GOLD => 'Gold',
            self::SILVER => 'Silver',
        };
    }


}


