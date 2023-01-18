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
        $wine_user_id = $request->wine_user_id;
        $drink_type_id = $request->drink_type_id;
        $country_id = $request->country_id;
        $region_id = $request->region_id;
        $producer_id = $request->producer_id;
        $type_of_wine = $request->type_of_wine;
        $wine_tag_id = $request->wine_tag_id;

        $product = Product::select('*');
        if(!empty($wine_user_id)){
            $product =  $product->where('wine_user_id');
        }
        if(!empty($drink_type_id)){
            $product =  $product->where('drink_type_id');
        }
        if(!empty($country_id)){
            $product =  $product->where('country_id');
        }
        if(!empty($region_id)){
            $product =  $product->where('region_id');
        }
        if(!empty($producer_id)){
            $product =  $product->where('producer_id');
        }
        if(!empty($type_of_wine)){
            $product =  $product->where('type_of_wine');
        }
        if(!empty($wine_tag_id)){
            $product =  $product->where('wine_tag_id');
        }

        $product = $product->paginate(10);

        return ProductResource::collection($product);
    }

    public function show(Request $request, $id)
    {
        return Product::whereId($id)->first();
    }
    public function store(ProductRequest $productRequest){
        try {
            $wine_user_id = \request()->user()->id;
            $productRequest->merge(['wine_user_id' =>$wine_user_id]);

            Product::create($productRequest->only(["wine_name", "description", "wine_user_id", "drink_type_id","bottle_size",
                "country_id","region_id","producer_id","wine_maker","type_of_wine","year","grape_varieties",
                "wine_tag_id","storage_condition","review_and_rewards","medal","award_name","score","review_name"]));

            return (new Success("Product store successfully."))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
}
