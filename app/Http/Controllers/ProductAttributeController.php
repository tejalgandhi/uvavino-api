<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductAttributeController extends Controller
{
    public function attribute_list(Request $request, String $attribute_name)
    {
        $conf = Arr::get(config('product_attributes'), $attribute_name);
        if (!$conf) {
            return "Not found";
        }
        return DB::table($conf['table'])
            ->select($conf['list_attrs'])
            ->get();
        
    }
}
