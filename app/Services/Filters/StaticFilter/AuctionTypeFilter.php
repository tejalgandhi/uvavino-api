<?php
namespace App\Services\Filters\StaticFilter;

use App\Enums\AuctionType;

class AuctionTypeFilter extends BaseFilter
{
    protected $enum = AuctionType::class;

}
