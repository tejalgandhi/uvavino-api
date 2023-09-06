<?php
namespace App\Services\Filters;

use App\Models\Brand;

class BrandFilter extends BaseFilter
{
    protected $model = Brand::class;
    protected $fields = [
        "id",
        "name",
    ];
}
