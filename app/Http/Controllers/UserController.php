<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionInviteRequest;
use App\Http\Resources\AuctionResource;
use App\Http\Resources\Fail;
use App\Models\Auction;
use App\Models\AuctionInvite;
use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function allProductCount(Request $request)
    {
        try {

            $data = [];
            $data['auctionsCount'] = Auction::where('created_by',$request->wine_user_id)->count();
            $data['productsCount'] = Basket::where('created_by',$request->wine_user_id)->where('is_auction_product',Basket::IS_NOT_AUCTION_PRODUCT)->count();
            $data['totalProductCount'] =  $data['auctionsCount']  +  $data['productsCount'];

            return response()->json(['message'=>"Get User Products Count","data"=>$data],201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }

    }

    public function allWishlistCount(Request $request)
    {
        try {

            $data = [];
            $data['auctionsWishlistCount'] = Auction::whereHas('wishlist',function($q) use($request)  {
                $q->where('wine_user_id',$request->wine_user_id);
            })->count();
            $data['productsWishlistCount'] = Basket::whereHas('wishlist',function($q) use($request)  {
                $q->where('wine_user_id',$request->wine_user_id);
            })->where('is_auction_product',Basket::IS_NOT_AUCTION_PRODUCT)->count();
            $data['totalProductWishlistCount'] =  $data['auctionsWishlistCount']  +  $data['productsWishlistCount'];

            return response()->json(['message'=>"Get User Wishlist Count","data"=>$data],201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
    public function getWishlist(Request $request)
    {
        try {
            $limit = $request->limit ?? config('constant.number_of_items_per_page');
            $user_id = $request->wine_user_id;
            $data = [];
            $wishlistAuctions = Auction::withCount(['bids', 'wishlist' => function ($q) use ($user_id) {
                    $q->where('wine_user_id', $user_id);
                }])->whereHas('wishlist',function($q) use($user_id)  {
                $q->where('wine_user_id',$user_id);
            });
            $data['wishlistAuctions'] = AuctionResource::collection(
                $wishlistAuctions->paginate($limit)
            );


            return response()->json(['message'=>"Get User Wishlist","data"=>$data],201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function getBuyPurchaseHistory(Request $request)
    {
        try {
            $limit = $request->limit ?? config('constant.number_of_items_per_page');
            $data = [];
            $buyAuctions = Auction::select(AuctionResource::selectLst())
                ->unfinishedAuctions()
                ->withCount(['bids', 'wishlist' => function ($q) use ($request) {
                    $q->where('wine_user_id', $request->wine_user_id);
                }])->whereHas('bids', function ($q) use ($request) {
                    $q->where('wine_user_id', $request->wine_user_id);
                });

            $data['buy_auctions'] = AuctionResource::collection(
                $buyAuctions->paginate($limit)
            );
            $purchaseAuctions = Auction::select(AuctionResource::selectLst())
                ->withCount(['bids', 'wishlist' => function ($q) use ($request) {
                    $q->where('wine_user_id', $request->wine_user_id);
                }])->whereHas('winnerBid', function ($q) use ($request) {
                    $q->where('wine_user_id', $request->wine_user_id);
                });

            $data['purchase_auctions'] = AuctionResource::collection(
                $purchaseAuctions->paginate($limit)
            );
            return response()->json(['message' => "Get User buy purchase history", "data" => $data], 201);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function getPrivateAuctions(Request $request)
    {
        try{
            $limit = $request->limit ?? config('constant.number_of_items_per_page');
            $data = [];
            $privateAuctions = Auction::privateAuctions()->withCount(['bids', 'wishlist' => function ($q) use ($request) {
                $q->where('wine_user_id', $request->wine_user_id);
            }]);
            $data['private_auctions'] = AuctionResource::collection(
                $privateAuctions->paginate($limit)
            );
            $inviteAuctions = Auction::inviteAuctions()->withCount(['bids', 'wishlist' => function ($q) use ($request) {
                $q->where('wine_user_id', $request->wine_user_id);
            }]);
            $data['invite_auctions'] = AuctionResource::collection(
                $inviteAuctions->paginate($limit)
            );

            return response()->json(['message' => "Get User Private Auctions", "data" => $data], 201);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function getParticipateAuction(Request $request)
    {
        try{
            $limit = $request->limit ?? config('constant.number_of_items_per_page');
            $participateAuctions = Auction::finishedAuctions()->participateAuctions()->withCount(['bids', 'wishlist' => function ($q) use ($request) {
                $q->where('wine_user_id', $request->wine_user_id);
            }]);
            $data = AuctionResource::collection(
                $participateAuctions->paginate($limit)
            );

            return response()->json($data->response()->getData(true), 201);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function getSellAuctions(Request $request)
    {
        try{
            $limit = $request->limit ?? config('constant.number_of_items_per_page');
            $participateAuctions = Auction::withCount(['bids', 'wishlist' => function ($q) use ($request) {
                $q->where('wine_user_id', $request->wine_user_id);
            }])->where('created_by',$request->wine_user_id)->orderByDesc('created_at');
            $data = AuctionResource::collection(
                $participateAuctions->paginate($limit)
            );
            return response()->json($data->response()->getData(true), 201);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function updateAuctionInvitation(AuctionInviteRequest $request)
    {
        try{
            $limit = $request->limit ?? config('constant.number_of_items_per_page');
            AuctionInvite::where('auction_id',$request->auction_id)->update(['status'=>$request->status]);
            $inviteAuctions = Auction::inviteAuctions()->withCount(['bids', 'wishlist' => function ($q) use ($request) {
                $q->where('wine_user_id', $request->wine_user_id);
            }]);
            $data['invite_auctions'] = AuctionResource::collection(
                $inviteAuctions->paginate($limit)
            );
            return response()->json(['message' => "Auction Invite Update Successfully." , "data" => $data], 201);

        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

}
