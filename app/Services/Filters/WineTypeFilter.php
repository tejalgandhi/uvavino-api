<?php
namespace App\Services\Filters;

use App\Models\WineType;

class WineTypeFilter extends BaseFilter
{
    protected $model = WineType::class;
    protected $fields = [
        "id",
        "name",
        "slug",
        "status",
        "is_approved",
    ];
}
