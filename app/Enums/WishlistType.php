<?php
namespace App\Enums;

Enum WishlistType: int {
    case AUCTION_PACKAGE = 1;
    case AUCTION = 2;
    case PRODUCT = 3;

    public function label()
    {
        return match($this) {
            self::AUCTION_PACKAGE => 'auction_package',
            self::AUCTION => 'auction'
            self::PRODUCT => 'product'
        };
    }

}


