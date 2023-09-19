<?php
namespace App\Enums;

Enum Year: int {
    case YEAR_ONE = 0;
    case YEAR_TWO = 1;
    case YEAR_THREE = 2;
    case YEAR_FOUR = 3;
    case YEAR_FIVE = 4;
    case YEAR_SIX = 5;
    case YEAR_SEVEN = 6;
    case YEAR_EIGHT = 7;
    case YEAR_NINE = 8;

    public function label()
    {
        return match($this) {
            self::YEAR_ONE => '2016',
            self::YEAR_TWO => '2017',
            self::YEAR_THREE => '2018',
            self::YEAR_FOUR => '2019',
            self::YEAR_FIVE => '2020',
            self::YEAR_SIX => '2021',
            self::YEAR_SEVEN => '2022',
            self::YEAR_EIGHT => '2023',
            self::YEAR_NINE => '2024',
        };
    }


}


