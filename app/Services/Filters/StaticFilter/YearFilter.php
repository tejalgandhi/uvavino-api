<?php
namespace App\Services\Filters\StaticFilter;

use App\Enums\Year;

class YearFilter extends BaseFilter
{
    protected $enum = Year::class;

}
