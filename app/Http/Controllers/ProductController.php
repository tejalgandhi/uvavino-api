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
            'wine_name' => 'can_name_list',
            'type_of_wine' => 'can_list',
            'drink_type_id'=> 'can_list',
            'country_id'=> 'can_list',
            'region_id'=> 'can_list',
            'producer_id'=> 'can_list',
            'wine_tag_id'=> 'can_list',
        ];

        $query = Product::query();
        foreach($filter_attrs as $attr => $logic) {
            if ($request->has($attr)) {
                if($logic == 'can_list') {
                    $query = $query->whereIn($attr, is_array( $request->attr) ?  $request->attr : [$request->attr]);
                }
                if($logic == 'can_name_list') {
                    $query = $query->where($attr, 'like', '%' . $request->attr . '%');
                }
            }
        }

        $products = $query->paginate(10);

        return ProductResource::collection($products);
    }

    public function show(Request $request, $id)
    {
        return Product::whereId($id)->first();
    }
    public function store(ProductRequest $request){
        try {
            $wine_user_id = request()->user()->id;
            $request->merge(['wine_user_id' =>$wine_user_id]);

            Product::create($request->only([
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
