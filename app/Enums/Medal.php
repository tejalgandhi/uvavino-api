<?php
namespace App\Enums;

Enum Medal: int {
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


