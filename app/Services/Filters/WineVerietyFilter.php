<?php
namespace App\Services\Filters;

use App\Models\WineVariety;

class WineVerietyFilter extends BaseFilter
{
    protected $model = WineVariety::class;
    protected $fields = [
        "id",
        "name",
        "slug",
        "status",
        "is_approved",
    ];
}
