<?php
namespace App\Services\Filters;

use App\Models\Region;

class RegionFilter extends BaseFilter
{
    protected $model = Region::class;
    protected $fields = [
        "id",
        "country_id",
        "name",
        "slug",
        "status",
    ];
}
