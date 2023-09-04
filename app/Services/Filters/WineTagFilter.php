<?php
namespace App\Services\Filters;

use App\Models\WineTag;

class WineTagFilter extends BaseFilter
{
    protected $model = WineTag::class;
    protected $fields = [
        "id",
        "name",
        "slug",
        "status",
        "is_approved",
    ];
}
