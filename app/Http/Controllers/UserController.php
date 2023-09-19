<?php

namespace App\Http\Controllers;

use App\Http\Resources\Fail;
use App\Models\Auction;
use App\Models\Basket;
use Illuminate\Http\Request;

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

}
