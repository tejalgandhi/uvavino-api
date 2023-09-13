<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionBidRequest;
use App\Http\Requests\AuctionCreateRequest;
use App\Http\Requests\AutomaticBidRequest;
use App\Http\Resources\AuctionDetailResource;
use App\Http\Resources\AuctionResource;
use App\Http\Resources\AuctionSingleProductDetailResource;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Models\Auction;
use App\Models\AuctionBid;
use App\Models\AutomaticBid;
use App\Models\Basket;
use App\Models\Product;
use App\Services\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Class AuctionController
 * @package App\Http\Controllers
 */
class AuctionController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $order_by = $request->order_by;
        $filter_attrs = [
            'auctions' => [
//                'title' => 'like',
                'type' => 'in',
                'price_greater_than' => 'greater',
                'price_less_than' => 'less',
                'baskets' => [
                    'slug' => 'like',
                    'products' => [
                        'wine_name' => 'like',
                        'type_of_wine' => 'in',
                        'drink_type_id' => 'in',
                        'country_id' => 'in',
                        'region_id' => 'in',
                        'producer_id' => 'in',
                        'wine_tag_id' => 'in',
                        'bottle_size' => 'in',
                        'year' => 'in',
                     ]
                ],
           ]
        ];

        $relationshipLogics = [
            'in' => function ($q, $a, $v) {
                return $q->whereIn($a, is_array($v) ? $v : [$v]);
            },
            'like' => function ($q, $a, $v) {
                return $q->where($a, 'LIKE', '%' . $v . '%');
            },
            'greater' => function ($q, $a, $v) {
                return $q->where($a, '>',  $v);
            },
            'less' => function ($q, $a, $v) {
                return $q->where($a, '<',  $v);
            }
        ];
        $query = Auction::select(AuctionResource::selectLst())
            ->withCount(['bids','wishlist'=> function($q) use($request)  {
                    $q->where('wine_user_id',$request->user_id);
            } ])
            ->with([
                'baskets',
                'baskets.products',
                'baskets.products.wine_user',
                'baskets.products.drink_type',
                'baskets.products.country',
                'baskets.products.region',
                'baskets.products.producer',
                'baskets.products.wine_type',
                'baskets.products.wine_tag',
            ]);

        /*
         * Logic of table and relationships conditions
         */
        foreach ($filter_attrs as $masterTbl => $allAttrs) {
            foreach ($allAttrs as $tbl => $attrs) {
                if (is_array($attrs)) {
                    foreach ($attrs as $attr => $relationshipLogic) {
                        if ($request->has($attr) && !is_array($relationshipLogic)) {
                            $query = $query->whereHas($tbl, function ($query) use ($attr, $request, $tbl, $relationshipLogics, $relationshipLogic) {
                                $relationshipLogics[$relationshipLogic]($query, implode(".", [$tbl, $attr]), $request->$attr);
                            });
                        }
                        if (is_array($relationshipLogic)) {
                            foreach ($relationshipLogic as $a => $r) {
                                if(!empty($request->$a)) {
                                    $table = $tbl . '.' . $attr;
                                    $query = $query->whereHas($table, function ($query) use ($a, $request, $attr, $relationshipLogics, $r) {
                                        $relationshipLogics[$r]($query, implode(".", [$attr, $a]), $request->$a);
                                    });
                                }
                            }
                        }
                    }
                } else {
                    if($request->$tbl != '') {
                        if($tbl == 'price_greater_than' || $tbl == 'price_less_than'){
                            $field = 'max_price';
                            $query = $relationshipLogics[$attrs]($query, implode(".", [$masterTbl, $field]), $request->$tbl);
                         }
                        else{
                            $query = $relationshipLogics[$attrs]($query, implode(".", [$masterTbl, $tbl]), $request->$tbl);
                        }
                    }
                }
            }
        }



        $query = match($order_by){
        'ascending' => $query->orderBy('auctions.title', 'asc'),
            'descending' => $query->orderBy('auctions.title', 'desc'),
            'recent' => $query->orderBy('auctions.created_at', 'desc'),
            'older' => $query->orderBy('auctions.created_at', 'asc'),
            'expensive' => $query->orderBy('auctions.max_price', 'desc'),
            'cheapest' => $query->orderBy('auctions.max_price', 'asc'),
            default => $query
        };

        return AuctionResource::collection(
            $query->paginate(10)
        );
    }

    /**
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|object|null
     */
    public function show(Request $request,$id)
    {
        $request->merge(['order_by' =>$request->order_by]);
        $auction = Auction::query()
            ->withCount(['bids','wishlist'=> function($q) use($request)  {
                $q->where('wine_user_id',$request->user_id);
            } ])
            ->where('id', $id);

          $auction = $auction->first();
        if (!$auction) {
            return (new Fail(__('Auction does not exist')))->json(404);
        }
        return new AuctionDetailResource($auction);
    }

    public function showSingleAuction(Request $request,$auction_id,Product $product)
    {
        $request->merge(['product' =>$product]);
        $auction = Auction::query()
            ->withCount(['bids','wishlist'=> function($q) use($request)  {
                $q->where('wine_user_id',$request->user_id);
            } ])
            ->where('id', $auction_id)
            ->first();
        if (!$auction) {
            return (new Fail(__('Auction does not exist')))->json(404);
        }
        return new AuctionSingleProductDetailResource($auction);
    }
    /**
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|object|null
     */
    public function destory(Request $request,Auction $auction)
    {
        try {
            $auction->delete();
            return (new Success(__('Auction delete Successfully')))->json(201);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json(500);
        }
    }


    /**
     * @param AuctionCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AuctionCreateRequest $request)
    {
        try {
            $basket = Basket::create(
                $request->only([
                    "title",
                    "price",
                ]));
            $products = $request->products;
            foreach ($products as $product) {

                $size = Config::get('auction_attributes.bottle_size.'.$product["bottle_size"]) ;
                $unit = Config::get('bottle_size.'.$size.'.unit');
                $unit_qty = Config::get('bottle_size.'.$size.'.unit_quantity');

                $createdProduct = Product::create([
                    "basket_id" => $basket->id,
                    "wine_name" => $product["title"],
                    "title" => $product["title"],
                    "description" => $product["description"],
                    "wine_user_id" => $request->wine_user_id,
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
                    "basket_quantity" => $product["stock"],
                    "unit_quantity" => $unit_qty,
                    "unit" => $unit,
                    "volume" => $product["volume"],
                    "price" => $product["price"],
                ]);
                if(isset($product['product_images'])){
                    $createdProduct->uploadProductImages('product_images',$product['product_images'],['products']);
                }
            }
            $request->request->add(['basket_id' => $basket->id]);

            Auction::create($request->only([
                "basket_id",
                "title",
                "is_publish",
                "start_date",
                "end_date",
                "type",
                "max_price",
                "invitations",
            ]));
            return (new Success(__('Auction Create Successfully')))->json(201);

        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json(500);
        }
    }

    /**
     * @param AuctionBidRequest $request
     * @param Auction $auction
     * @return Fail|\Illuminate\Http\JsonResponse
     */
    public function bid(AuctionBidRequest $request, Auction $auction)
    {
        // Check if value is valid or not.
        $value = $request->value;
        if(!$auction->canBid($value)) {
            return (new Fail(__('Could not bid with ' . $value)));
        }
        AuctionBid::create([
            'auction_id' => $auction->id,
            'wine_user_id' => $request->wine_user_id,
            'value' => $request->value
        ]);
        return (new Success(__('Auction Bit Create Successfully')))->json(201);
    }

    /**
     * @param AutomaticBidRequest $request
     * @param Auction $auction
     * @return \Illuminate\Http\JsonResponse
     */
    public function automaticBid(AutomaticBidRequest $request, Auction $auction)
    {
        $max = $request->max_amount;
        if ($auction->max_price != null && $max < $auction->max_price) {
            return (new Fail(__('You should buy without auction in that price.')))->json(400);
        }
        if ($max < $auction->nextMinBid()) {
            return (new Fail(__('Your auto bid amount is too low.')))->json(400);
        }
        AutomaticBid::create([
            'auction_id' => $auction->id,
            'wine_user_id' => $request->wine_user_id,
            'max_amount' => $request->max_amount
        ]);
        return (new Success(__('Auction Bit Create Successfully')))->json(201);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getBestSellers()
    {
        $product = Auction::getBestSellersProduct();
        return  AuctionResource::collection($product)->additional(['meta' => []]);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getNewProducts()
    {
        $product = Auction::getNewProduct();
        return AuctionResource::collection($product)->additional(['meta' => []]);
    }

    public function attribute_list(Request $request,Attribute $attribute)
    {
        $conf = config('auction_attributes');
        if(!empty($conf)){
            foreach ($conf as $key => $data){
                $conf[$key] = is_array($data) ? $data: app($data)->getData();
            }
        }

        return $conf;

    }
}
