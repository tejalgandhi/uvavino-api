<?php
namespace App\Enums;

Enum Year: string {
    case YEAR_ONE = '2016';
    case YEAR_TWO = '2017';
    case YEAR_THREE = '2018';
    case YEAR_FOUR = '2019';
    case YEAR_FIVE = '2020';
    case YEAR_SIX = '2021';
    case YEAR_SEVEN = '2022';
    case YEAR_EIGHT = '2023';
    case YEAR_NINE = '2024';

    public function value()
    {
        return match($this) {
            self::YEAR_ONE => 0,
            self::YEAR_TWO => 1,
            self::YEAR_THREE => 2,
            self::YEAR_FOUR => 3,
            self::YEAR_FIVE => 4,
            self::YEAR_SIX => 5,
            self::YEAR_SEVEN => 6,
            self::YEAR_EIGHT => 7,
            self::YEAR_NINE => 8,
        };
    }


}


