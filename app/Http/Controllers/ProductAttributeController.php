<?php

namespace App\Http\Controllers;

use App\Http\Resources\Fail;
use App\Http\Resources\ProductAttributeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductAttributeController extends Controller
{
    public function attribute_list(Request $request, String $attribute_name)
    {
        $conf = Arr::get(config('product_attributes'), $attribute_name);
        if (!$conf) {
            return (new Fail(__('product_attributes.invalid', compact('attribute_name'))))->json(404);
        }
        $query = $conf['model']::query();
            
        if (Arr::has($conf, 'with')) {
            $query->with(Arr::get($conf, 'with', []));
        }

        // Adding list attributes to request to use it in the resource.
        $request->merge(['list_attrs' => $conf['list_attrs']]);

        return ProductAttributeResource::collection($query->get());
        
    }
}
