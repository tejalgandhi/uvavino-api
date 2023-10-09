<?php

namespace App\Http\Controllers;

use App\Enums\BottleSize;
use App\Enums\AuctionType;
use App\Http\Requests\AuctionBidRequest;
use App\Http\Requests\AuctionCreateRequest;
use App\Http\Requests\AuctionUpdateRequest;
use App\Http\Requests\AutomaticBidRequest;
use App\Http\Requests\InviteMemberRequest;
use App\Http\Resources\AuctionDetailResource;
use App\Http\Resources\AuctionResource;
use App\Http\Resources\AuctionSingleProductDetailResource;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Mail\InvitationMail;
use App\Mail\InvitePeopleMail;
use App\Models\Auction;
use App\Models\AuctionBid;
use App\Models\AuctionInvite;
use App\Models\AutomaticBid;
use App\Models\Basket;
use App\Models\Product;
use App\Services\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Jobs\VerifyLiveAuctionEnd;
use App\Jobs\VerifyTimebasedAuctionEnd;

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
        $in_wishlist = $request->is_wishlist ?? '';
        $limit = $request->limit ?? config('constant.number_of_items_per_page') ;
        $filter_attrs = [
            'auctions' => [
//                'title' => 'like',
                'type' => 'in',
                'created_by' => 'in',
                'price_greater_than' => 'greater',
                'price_less_than' => 'less',
                'baskets' => [
                    'slug' => 'like',
                    'products' => [
                        'wine_name' => 'like',
                        'wine_type_id' => 'in',
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
                return $q->where($a, '>', $v);
            },
            'less' => function ($q, $a, $v) {
                return $q->where($a, '<', $v);
            }
        ];
        $query = Auction::publishAuctions()
            ->withCount(['bids', 'wishlist' => function ($q) use ($request) {
                $q->where('wine_user_id', $request->user_id);
            }])
            ->with([
                'baskets',
                'baskets.products',
                'baskets.products.wine_user',
                'baskets.products.country',
                'baskets.products.region',
                'baskets.products.producer',
                'baskets.products.wine_type',
                'baskets.products.wine_tag',
            ]);
        //wishlist tab in user profile
        if ($in_wishlist) {
            $query = $query->whereHas('wishlist', function ($q) use ($request) {
                $q->where('wine_user_id', $request->user_id);
            });
        }
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
                                if (!empty($request->$a)) {
                                    $table = $tbl . '.' . $attr;
                                    $query = $query->whereHas($table, function ($query) use ($a, $request, $attr, $relationshipLogics, $r) {
                                        $relationshipLogics[$r]($query, implode(".", [$attr, $a]), $request->$a);
                                    });
                                }
                            }
                        }
                    }
                } else {
                    if ($request->$tbl != '') {
                        if ($tbl == 'price_greater_than' || $tbl == 'price_less_than') {
                            $field = 'max_price';
                            $query = $relationshipLogics[$attrs]($query, implode(".", [$masterTbl, $field]), $request->$tbl);
                        } else {
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
            $query->paginate($limit)
        );
    }

    /**
     * @param AuctionCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AuctionCreateRequest $request)
    {
        try {
            $basket = Basket::create([
                "title" => $request->title,
                "price" => $request->price,
                "is_auction_product" => Basket::IS_AUCTION_PRODUCT,
            ]);
            $products = $request->products;
            foreach ($products as $product) {
                $createdProduct = Product::create([
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
            $request->request->add(['basket_id' => $basket->id]);

            $auction = Auction::create($request->only([
                "basket_id",
                "title",
                "description",
                "is_private",
                "is_publish",
                "start_date",
                "end_date",
                "type",
                "max_price",
            ]));
            $invite_peoples = $request->invite_peoples;
            if ($invite_peoples) {
                foreach ($invite_peoples as $invite_people) {
                    Mail::to($invite_people)->queue(new InvitationMail(Auth::User()));
                    AuctionInvite::create([
                        "auction_id" => $auction->id,
                        "email" => $invite_people,
                    ]);
                }
            }

            if($auction->type == AuctionType::TIMEBASED)
            {
                dispatch(new VerifyTimebasedAuctionEnd($auction))->delay($auction->end_date->addMinutes(1));

            }
            if($auction->type == AuctionType::LIVE)
            {
                dispatch(new VerifyLiveAuctionEnd($auction))->delay($auction->start_date->addSeconds(config('constant.configured_auction_time')));
            }

            return (new Success(__('Auction Create Successfully')))->json(201);

        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json(500);
        }
    }

    public function updateInviteMember(InviteMemberRequest $request)
    {
        try{
            $invite_peoples = $request->invite_peoples;
            if ($invite_peoples) {
                foreach ($invite_peoples as $invite_people) {
                    $auctions = AuctionInvite::where('auction_id',$request->auction_id)->pluck('email')->toArray();
                    $diff = array_diff($invite_peoples, $auctions);
                    if(!empty($diff)) {
                        Mail::to($invite_people)->queue(new InvitationMail(Auth::User()));
                        AuctionInvite::create([
                            "auction_id" => $request->auction_id,
                            "email" => $invite_people,
                        ]);
                    }
                }
            }
            $inviteMembers = AuctionInvite::where('auction_id',$request->auction_id)->get();
            return response()->json(['message' => "Invite member add Successfully", "data" => $inviteMembers], 201);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json(500);
        }
    }

    /**
     * @param AuctionCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Auction $auction)
    {
        try {
            \Log::info($request->all());
            $invitee = $auction->invites->pluck('email')->toArray();
            
            Basket::where('id', $auction->basket_id)->update([
                "title" => $request->title,
                "price" => $request->price,
            ]);
            \Log::info('Basket updated');

            $products = $request->products;
            foreach ($products as $product) {
                $createdProduct = [
                    "basket_id" => $auction->basket_id,
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
                ];
                $updateProduct = Product::updateOrCreate(['id' => $product['id']], $createdProduct);
                if (isset($product['product_images'])) {
                    $updateProduct->uploadImages('product_images', $product['product_images'], ['products']);
                }
            }
            \Log::info('Product updated');
            Auction::where('id', $auction->id)->update(
                $request->only([
                "basket_id",
                "title",
                "description",
                "is_private",
                "is_publish",
                "start_date",
                "end_date",
                "type",
                "max_price",
                ])
            );
            \Log::info('Auction updated');
            if($auction->end_date != $request->end_date && $request->type == AuctionType::TIMEBASED)
            {
                dispatch(new VerifyTimebasedAuctionEnd($auction))->delay($auction->end_date->addMinutes(1));

            }
            if($auction->start_date != $request->start_date && $request->type == AuctionType::LIVE)
            {
                dispatch(new VerifyLiveAuctionEnd($auction))->delay($auction->start_date->addSeconds(config('constant.configured_auction_time')));
            }
            $invite_peoples = $request->invite_peoples;
            if ($invite_peoples) {
                $diff = array_diff($invite_peoples, $invitee);
                $remove_invites = array_diff($invitee, $invite_peoples);
                if (!empty($diff)) {
                    foreach ($diff as $invite_people) {
                        Mail::to($invite_people)->queue(new InvitationMail(Auth::User()));
                        AuctionInvite::create([
                            "auction_id" => $auction->id,
                            "email" => $invite_people,
                        ]);
                    }
                }
                if (!empty($remove_invites)) {
                    AuctionInvite::whereIn('email', $remove_invites)->where('auction_id', $auction->id)->delete();
                }
            }



            return (new Success(__('Auction Updated Successfully')))->json(201);

        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json(500);
        }
    }

    /**
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|object|null
     */
    public function show(Request $request, $id)
    {
        $request->merge(['order_by' => $request->order_by]);
        $auction = Auction::query()
            ->withCount(['bids', 'wishlist' => function ($q) use ($request) {
                $q->where('wine_user_id', $request->user_id);
            }])
            ->where('id', $id);

        $auction = $auction->first();
        if (!$auction) {
            return (new Fail(__('Auction does not exist')))->json(404);
        }
        return new AuctionDetailResource($auction);
    }

    public function showSingleAuction(Request $request, $auction_id, Product $product)
    {
        $request->merge(['product' => $product]);
        $auction = Auction::query()
            ->withCount(['bids', 'wishlist' => function ($q) use ($request) {
                $q->where('wine_user_id', $request->user_id);
            }])
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
    public function destory(Request $request, Auction $auction)
    {
        try {
            $auction->delete();
            return (new Success(__('Auction delete Successfully')))->json(201);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json(500);
        }
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
        return AuctionResource::collection($product)->additional(['meta' => []]);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getNewProducts()
    {
        $product = Auction::getNewProduct();
        return AuctionResource::collection($product)->additional(['meta' => []]);
    }

    public function attribute_list(Request $request, Attribute $attribute)
    {
        $conf = config('auction_attributes');
        if (!empty($conf)) {
            foreach ($conf as $key => $data) {
                $conf[$key] = is_array($data) ? $data : app($data)->getData();
            }
        }

        return $conf;

    }
}
