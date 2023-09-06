<?php
namespace App\Services\Filters;

use App\Models\Country;

class CountryFilter extends BaseFilter
{
    protected $model = Country::class;
    protected $fields = [
        "id",
        "name",
    ];
}
