<?php
namespace App\Services\Filters\StaticFilter;

use App\Enums\BottleSize;

class BottleSizeFilter extends BaseFilter
{
    protected $enum = BottleSize::class;

}
