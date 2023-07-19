<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasketRequest;
use App\Http\Resources\BasketResource;
use App\Http\Resources\Fail;
use App\Http\Resources\ProductResource;
use App\Http\Resources\Success;
use App\Models\Basket;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $order_by = $request->order_by;

        $filter_attrs = [
            'baskets' => [
                'title' => 'like',
                'slug' => 'like',
                'products' => [
                    'wine_name' => 'like',
                    'type_of_wine' => 'in',
                    'drink_type_id' => 'in',
                    'country_id' => 'in',
                    'region_id' => 'in',
                    'producer_id' => 'in',
                    'wine_tag_id' => 'in',
                ]
            ],
        ];

        $relationshipLogics = [
            'in' => function ($q, $a, $v) {
                return $q->whereIn($a, is_array($v) ? $v : [$v]);
            },
            'like' => function ($q, $a, $v) {
                return $q->where($a, 'LIKE', '%' . $v . '%');
            }
        ];
        $query = Basket::query()
            ->with(['products',
                'products.wine_user',
                'products.drink_type',
                'products.country',
                'products.region',
                'products.producer',
                'products.wine_type',
                'products.wine_tag',
            ]);

        /*
         * Logic of table and relationships conditions
         */
        foreach ($filter_attrs as $masterTbl => $allAttrs) {
            foreach ($allAttrs as $tbl => $attrs) {
                if (is_array($attrs)) {
                    foreach ($attrs as $attr => $relationshipLogic) {
                        if ($request->has($attr)) {
                            $query = $query->whereHas($tbl, function ($query) use ($attr, $request, $tbl, $relationshipLogics, $relationshipLogic) {
                                $relationshipLogics[$relationshipLogic]($query, implode(".", [$tbl, $attr]), $request->$attr);
                            });

                        }
                    }
                } else {
                    $query = $relationshipLogics[$attrs]($query, implode(".", [$masterTbl, $tbl]), $request->$tbl);
                }
            }
        }

        $query = match($order_by){
            'ascending' => $query->orderBy('baskets.title', 'asc'),
            'descending' => $query->orderBy('baskets.title', 'desc'),
            'recent' => $query->orderBy('baskets.created_at', 'desc'),
            'older' => $query->orderBy('baskets.created_at', 'asc'),
            'expensive' => $query->orderBy('baskets.price', 'desc'),
            'cheapest' => $query->orderBy('baskets.price', 'asc'),
            default => $query
        };
        return BasketResource::collection(
            $query->select(
                BasketResource::selectLst()
            )->paginate(10)
        );
    }

    /**
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|object|null
     */
    public function show($slug)
    {
        $product = Basket::query()
            ->with('products')
            ->where('baskets.slug', $slug)
            ->first();
        if (!$product) {
            return (new Fail(__('Product does not exist')))->json(404);
        }
        return $product;
    }

    /**
     * @param BasketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BasketRequest $request)
    {
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
            $products = $request->products;
            foreach ($products as $product) {
                Product::create([
                    "basket_id" => $basket->id,
                    "wine_name" => $product["wine_name"],
                    "description" => $product["description"],
                    "wine_user_id" =>$request->wine_user_id,
                    "drink_type_id" => $product["drink_type_id"],
                    "bottle_size" => $product["bottle_size"],
                    "country_id" => $product["country_id"],
                    "region_id" => $product["region_id"],
                    "producer_id" => $product["producer_id"],
                    "wine_maker" => $product["wine_maker"],
                    "type_of_wine" => $product["type_of_wine"],
                    "year" => $product["year"],
                    "grape_̥varieties" => $product["grape_̥varieties"],
                    "wine_tag_id" => $product["wine_tag_id"],
                    "storage_condition" => $product["storage_condition"],
                    "review_and_rewards" => $product["review_and_rewards"],
                    "medal" => $product["medal"],
                    "award_name" => $product["award_name"],
                    "score" => $product["score"],
                    "review_name" => $product["review_name"],
                    "basket_quantity" => $product["basket_quantity"],
                    "unit_quantity" => $product["unit_quantity"],
                    "unit" => $product["unit"],
                ]);
            }
            return (new Success(__("Product store successfully.")))->json();
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    //WIP: Product recommended Logic

    /**
     * @param $slug
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function recommended($slug)
    {
        $product = Product::whereSlug($slug)->first();
        if (!$product) {
            return (new Fail(__('Product does not exist')))->json(404);
        }
        return ProductResource::collection($product->recommended());
    }
    public function getBestSellers()
    {
        $product = Basket::getBestSellersProduct();
        $collections =  BasketResource::collection($product)->additional(['meta' => []]);
        return $collections;
    }

}
