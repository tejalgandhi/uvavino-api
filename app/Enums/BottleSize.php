<?php
namespace App\Enums;

Enum BottleSize: string {
    case TEN_ML = '10ml';
    case TWENTY_ML = '20ml';

    public function value()
    {
        return match($this) {
            self::TEN_ML => 0,
            self::TWENTY_ML => 1,
        };
    }


}


