<?php
namespace App\Services\Filters\StaticFilter;

use App\Enums\Medal;

class MedalFilter extends BaseFilter
{
    protected $enum = Medal::class;

}
