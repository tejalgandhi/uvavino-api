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
use Illuminate\Support\Facades\DB;
use App\Enums\BottleSize;
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
        $in_wishlist = $request->is_wishlist ?? '';
        $limit = $request->limit ?? config('constant.number_of_items_per_page') ;
        $filter_attrs = [
            'baskets' => [
                'title' => 'like',
                'slug' => 'like',
                'created_by' => 'in',
                'products' => [
                    'wine_name' => 'like',
                    'wine_type_id' => 'in',
                    'country_id' => 'in',
                    'region_id' => 'in',
                    'producer_id' => 'in',
//                    'wine_tag' => 'json_decode_in',
                ]
            ],
        ];

        $relationshipLogics = [
            'in' => function ($q, $a, $v) {
                return $q->whereIn($a, is_array($v) ? $v : [$v]);
            },
//            'json_decode_in' => function ($q, $a, $v) {
//                return $q->whereRaw('FIND_IN_SET('.$v.','. $a.')');
//            },
            'like' => function ($q, $a, $v) {
                return $q->where($a, 'LIKE', '%' . $v . '%');
            }
        ];
        $query = Basket::select(BasketResource::selectLst())
           ->where('is_auction_product',Basket::IS_NOT_AUCTION_PRODUCT)
            ->withCount(['wishlist'=> function($q) use($request)  {
                $q->where('wine_user_id',$request->user_id);
            } ])
            ->with(['products',
                'products.wine_user',
                'products.country',
                'products.region',
                'products.producer',
                'products.wine_type',
                'products.wine_tag',
            ]);
        if($in_wishlist){
            $query = $query->whereHas('wishlist',function($q) use($request)  {
                $q->where('wine_user_id',$request->user_id);
            });
        }
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
                    if($request->$tbl != '') {
                            $query = $relationshipLogics[$attrs]($query, implode(".", [$masterTbl, $tbl]), $request->$tbl);
                    }
                }
            }
        }
        $query->where('baskets.is_auction_product',Basket::IS_NOT_AUCTION_PRODUCT);

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
            $query->paginate($limit)
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
            ->withCount(['wishlist'=> function($q)   {
                $q->where('wine_user_id',request()->user_id);
            } ])
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
                $createdProduct =  Product::create([
                    "basket_id" => $basket->id,
                    "wine_name" => $product["wine_name"],
                    "title" => $product["wine_name"],
                    "description" => $product["description"],
                    "wine_user_id" => $request->wine_user_id,
                    "bottle_size" => $product["bottle_size"],
                    "country_id" => $product["country_id"],
                    "region_id" => $product["region_id"],
                    "producer_id" => $product["producer_id"],
                    "wine_maker" => $product["wine_maker"],
                    "wine_type_id" => $product["wine_type_id"],
                    "year" => $product["year"],
                    "wine_variety" => implode(",", $product["wine_variety"]),
                    "wine_tag" => implode(",", $product["wine_tag"]),
                    "storage_condition" => $product["storage_condition"],
                    "review_and_rewards" => $product["review_and_rewards"],
                    "basket_quantity" => $product["basket_quantity"],
                    "unit_quantity" => BottleSize::tryFrom($product["bottle_size"])->unit_quantity(),
                    "unit" => BottleSize::tryFrom($product["bottle_size"])->unit(),
                    "volume" => $product["volume"],
                    "price" => $product["price"],
                ]);
                if (isset($product['product_images'])) {
                    $createdProduct->uploadImages('product_images', $product['product_images'], ['products']);
                }
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
