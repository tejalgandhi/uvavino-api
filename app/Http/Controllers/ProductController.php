<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\ProductResource;
use App\Http\Resources\Success;
use App\Models\DrinkType;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $order_by = $request->order_by;

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

        $query = Product::query()
            ->leftJoin('wine_users', 'products.wine_user_id', '=', 'wine_users.id')
            ->leftJoin('drink_types', 'products.drink_type_id', '=', 'drink_types.id')
            ->leftJoin('countries', 'products.country_id', '=', 'countries.id')
            ->leftJoin('regions', 'products.region_id', '=', 'regions.id')
            ->leftJoin('producers', 'products.producer_id', '=', 'producers.id')
            ->leftJoin('wine_types', 'products.type_of_wine', '=', 'wine_types.id')
            ->leftJoin('wine_tags', 'products.wine_tag_id', '=', 'wine_tags.id')
            ->leftJoin('packaging', 'products.id', '=', 'packaging.product_id');
        foreach($filter_attrs as $attr => $logic) {
            if ($request->has($attr)) {
                $query = $logics[$logic]($query, $attr, $request->$attr);
            }
        }

        $query = match($order_by) {
            'a-z' => $query->orderBy('products.wine_name', 'asc'),
            'z-a' => $query->orderBy('products.wine_name', 'desc'),
            'recent' => $query->orderBy('products.created_at', 'desc'),
            'older' => $query->orderBy('products.created_at', 'asc'),
            'expensive' => $query->orderBy('packaging.price', 'desc'),
            'cheapest' => $query->orderBy('packaging.price', 'asc'),
            default => $query
        };

        return $query->select(ProductResource::selectLst())->paginate(10);
    }

    public function show($slug)
    {
        $product =  Product::whereSlug($slug)->first();
        if(!$product){
            return (new Fail(__('Product does not exist')))->json(404);
        }
        return $product;
    }

    public function store(ProductRequest $request){
        try {
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

    public function recommended($slug) {
        $product =  Product::whereSlug($slug)->first();
        if(!$product){
            return (new Fail(__('Product does not exist')))->json(404);
        }
        return ProductResource::collection($product->recommended());
    }
}
