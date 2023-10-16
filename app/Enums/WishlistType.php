<?php
namespace App\Enums;

use App\Models\Auction;
use App\Models\AuctionPackage;
use App\Models\Basket;
use App\Models\Product;
use ArchTech\Enums\Values;

Enum WishlistType: int {
    use Values;

    case auction_package = 1;
    case auction = 2;
    case product = 3;

    public function className(): string
    {
        return match($this){
            self::auction_package => AuctionPackage::class,
            self::auction => Auction::class,
            self::product =>  Basket::class
        };
    }
    public static function fromClassName(string $className): ?self
    {
        return match($className) {
            AuctionPackage::class => self::auction_package,
            Auction::class => self::auction,
            Basket::class => self::product,
            default => null,
        };
    }

}


