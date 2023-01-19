<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\ProductResource;
use App\Http\Resources\Success;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filter_attrs = [
            'wine_name' => 'like',
            'type_of_wine' => 'in',
            'drink_type_id' => 'in',
            'country_id' => 'in',
            'region_id' => 'in',
            'producer_id' => 'in',
            'wine_tag_id' => 'in',
        ];

        $logics = [
            'in' =>  function($q, $a, $v) { return $q->whereIn($a, is_array($v)? $v: [$v]); },
            'like' =>  function($q, $a, $v) { return $q->where($a,'LIKE','%'.$v.'%'); }
        ];

        $query = Product::query();
        foreach($filter_attrs as $attr => $logic) {
            if ($request->has($attr)) {
                $query = $logics[$logic]($query, $attr, $request->$attr);
            }
        }
        $products = $query->paginate(10);
        return ProductResource::collection($products);
    }

    public function show(Request $request, $id)
    {
        return Product::whereId($id)->first();
    }
    public function store(ProductRequest $productRequest){
        try {
            $wine_user_id = request()->user()->id;
            $productRequest->merge(['wine_user_id' =>$wine_user_id]);

            Product::create($productRequest->only([
                "wine_name",
                "description",
                "wine_user_id",
                "drink_type_id",
                "bottle_size",
                "country_id",
                "region_id",
                "producer_id",
                "wine_maker",
                "type_of_wine",
                "year",
                "grape_varieties",
                "wine_tag_id",
                "storage_condition",
                "review_and_rewards",
                "medal",
                "award_name",
                "score",
                "review_name"
            ]));

            return (new Success(__("Product store successfully.")))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
}
