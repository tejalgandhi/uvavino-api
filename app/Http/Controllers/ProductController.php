<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\ProductResource;
use App\Http\Resources\Success;
use App\Models\Basket;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $order_by = $request->order_by;

        $filter_attrs = [
            'products' => [
                'wine_name' => 'like',
                'type_of_wine' => 'in',
                'drink_type_id' => 'in',
                'country_id' => 'in',
                'region_id' => 'in',
                'producer_id' => 'in',
                'wine_tag_id' => 'in',
            ]
        ];

        $logics = [
            'in' =>  function($q, $a, $v) { return $q->whereIn($a, is_array($v)? $v: [$v]); },
            'like' =>  function($q, $a, $v) { return $q->where($a,'LIKE','%'.$v.'%'); }
        ];

        $query = Basket::query()
            ->leftJoin('products', 'baskets.id', '=', 'products.basket_id')
            ->leftJoin('wine_users', 'products.wine_user_id', '=', 'wine_users.id')
            ->leftJoin('drink_types', 'products.drink_type_id', '=', 'drink_types.id')
            ->leftJoin('countries', 'products.country_id', '=', 'countries.id')
            ->leftJoin('regions', 'products.region_id', '=', 'regions.id')
            ->leftJoin('producers', 'products.producer_id', '=', 'producers.id')
            ->leftJoin('wine_types', 'products.type_of_wine', '=', 'wine_types.id')
            ->leftJoin('wine_tags', 'products.wine_tag_id', '=', 'wine_tags.id');
        foreach($filter_attrs as $tbl => $attrs) {
            foreach($attrs as $attr => $logic) {
                if ($request->has($attr)) {
                    $query = $logics[$logic]($query, implode(".", [$tbl,$attr]), $request->$attr);
                }
            }
        }

        $query = match($order_by) {
            'ascending' => $query->orderBy('products.wine_name', 'asc'),
            'descending' => $query->orderBy('products.wine_name', 'desc'),
            'recent' => $query->orderBy('products.created_at', 'desc'),
            'older' => $query->orderBy('products.created_at', 'asc'),
            'expensive' => $query->orderBy('baskets.price', 'desc'),
            'cheapest' => $query->orderBy('baskets.price', 'asc'),
            default => $query
        };

        return ProductResource::collection(
            $query->select(
                ProductResource::selectLst()
            )->paginate(10)
        );
    }

    public function show($slug)
    {
        $product = Basket::query()
            ->leftJoin('products', 'baskets.id', '=', 'products.basket_id')
            ->where('baskets.slug',$slug)
            ->first();
        if(!$product){
            return (new Fail(__('Product does not exist')))->json(404);
        }
        return $product;
    }

    public function store(ProductRequest $request){
        try {
           $basket = Basket::create(
                $request->only([
                    "title",
                    "description",
                    "stock",
                    "price",
                    "weight",
                    "dimensions",
            ]));
            $request->request->add(['basket_id' => $basket->id]);
            Product::create($request->only([
                "basket_id",
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

    //WIP: Product recommended Logic
    public function recommended($slug) {
        $product =  Product::whereSlug($slug)->first();
        if(!$product){
            return (new Fail(__('Product does not exist')))->json(404);
        }
        return ProductResource::collection($product->recommended());
    }
}
