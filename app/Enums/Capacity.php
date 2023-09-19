<?php
namespace App\Enums;

use ArchTech\Enums\Values;

Enum Capacity: int {
    use Values;

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


