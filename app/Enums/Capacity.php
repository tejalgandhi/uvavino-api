<?php
namespace App\Enums;

Enum Capacity: string {
    case ML = 'ml';
    case LITER = 'liters';

    public function value()
    {
        return match($this) {
            self::ML => 0,
            self::LITER => 1,
        };
    }


}


