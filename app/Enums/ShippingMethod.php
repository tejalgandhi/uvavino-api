<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

Enum ShippingMethod: int {
    use InvokableCases;
    use Values;

case STANDARD = 1;
case EXPRESS = 2;

    public function label(): string
    {
        return match($this){
        self::STANDARD => 'Standard (free)',
            self::EXPRESS => 'Express'
        };
    }

}


