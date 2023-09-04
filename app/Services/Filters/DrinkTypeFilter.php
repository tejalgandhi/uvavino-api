<?php
namespace App\Services\Filters;

use App\Models\DrinkType;

class DrinkTypeFilter extends BaseFilter
{
    protected $model = DrinkType::class;
    protected $fields = [
        "id",
        "name",
        "slug",
        "status",
        "is_approved",
    ];
}
