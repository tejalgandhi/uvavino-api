<?php
namespace App\Enums;

Enum BottleSize: int {
    case TEN_ML = 0;
    case TWENTY_ML = 1;

    public function label()
    {
        return match($this) {
            self::TEN_ML => '10ml',
            self::TWENTY_ML => '20ml',
        };
    }


}


