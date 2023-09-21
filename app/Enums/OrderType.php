<?php
namespace App\Enums;

use ArchTech\Enums\Values;

Enum OrderType: int {
    use Values;

    case auction_package = 1;
    case auction = 2;
    case product = 3;


}


