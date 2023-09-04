<?php
namespace App\Services\Filters;

use App\Models\Producer;

class ProducerFilter extends BaseFilter
{
    protected $model = Producer::class;
    protected $fields = [
        "id",
        "name",
        "slug",
        "status",
        "is_approved",
    ];
}
