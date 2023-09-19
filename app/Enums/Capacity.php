<?php
namespace App\Enums;

Enum Capacity: int {
    case ML = 0;
    case LITER = 1;

    public function label()
    {
        return match($this) {
            self::ML => 'ML',
            self::LITER => 'L',
        };
    }


}


