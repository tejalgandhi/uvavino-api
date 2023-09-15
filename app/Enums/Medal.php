<?php
namespace App\Enums;

Enum Medal: string {
    case PLATINUM = 'Platinum';
    case GOLD = 'Gold';
    case SILVER = 'Silver';

    public function value()
    {
        return match($this) {
            self::PLATINUM => 0,
            self::GOLD => 1,
            self::SILVER => 2,
        };
    }


}


