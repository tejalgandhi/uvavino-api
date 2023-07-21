<?php
namespace App\Enums;

Enum ReviewType: string {

    case Product = 'App\Models\Product';
    case Auction = 'App\Models\Auction';
    case AuctionPack = 'App\Models\AuctionPackage';

}
