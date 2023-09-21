<?php
namespace App\Enums;

use ArchTech\Enums\Values;

Enum BottleSize: int {
    use Values;

    case TEN_ML = 0;
    case TWENTY_ML = 1;

    public function label(): string
    {
        return match($this) {
            self::TEN_ML => '10ml',
            self::TWENTY_ML => '20ml',
        };
    }

    public function unit(): string
    {
        return match($this) {
            self::TEN_ML => 'ml',
            self::TWENTY_ML => 'ml',
        };
    }

    public function unit_quantity(): string
    {
        return match($this) {
            self::TEN_ML => '10',
            self::TWENTY_ML => '20',
        };
    }

}


