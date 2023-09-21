<?php
namespace App\Services\Filters\StaticFilter;

use App\Enums\Capacity;

class CapacityFilter extends BaseFilter
{
    protected $enum = Capacity::class;

}
