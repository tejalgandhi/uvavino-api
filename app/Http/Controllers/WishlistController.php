<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Http\Resources\WishlistResource;
use App\Models\Auction;
use App\Models\Basket;
use App\Models\WineUser;
use App\Models\Wishlist as UserWishlist;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\WishlistType;


class WishlistController extends Controller
{
    public function index(Request $request)
    {

        $wishlist = Wishlist::where('wine_user_id',Auth::id());
        $type = $request->type;
        if($type){
            $wishlistable_type = WishlistType::from($type)->className();
            $wishlist = $wishlist->where('wishlistable_type',$wishlistable_type);
        }
        $wishlist = $wishlist->get();
        return WishlistResource::collection($wishlist);
    }

    public function store(WishlistRequest $request)
    {
        try {
            $type = $request->type;
            $wishlistable_id = $request->ref_id;
            $wishlistable_type = WishlistType::from($type)->className();
            $request->merge(['wishlistable_type' => $wishlistable_type]);
            $request->merge(['wishlistable_id' => $wishlistable_id]);

            $wishlist = UserWishlist::where($request->only(['wine_user_id', 'wishlistable_id','wishlistable_type']))->count();
            if ($wishlist > 0) {
                return response()->json(['data'=>[],'status'=>204 ,'message'=>__('Product already exists')],204) ;
            }
            if($request->wishlistable_type == WishlistType::product->className()) {
                $basket = Basket::where('id', $request->wishlistable_id)->count();
                if ($basket == 0) {
                    return response()->json(['data' => [], 'status' => 204, 'message' => __('Product not found.')], 204);
                }
            }
            if($request->wishlistable_type == WishlistType::auction->className()) {
                $auction = Auction::where('id', $request->wishlistable_id)->count();
                if ($auction == 0) {
                    return response()->json(['data' => [], 'status' => 204, 'message' => __('Auction not found.')], 204);
                }
            }
            DB::enableQueryLog();
            UserWishlist::create($request->only([
                "wine_user_id",
                "wishlistable_id",
                "wishlistable_type"
            ]));
            return response()->json(['data'=>[],'status'=>201 ,'message'=>__('Wishlist store successfully.')],201) ;

        }catch (\Exception $e){

            Log::info($e->getMessage());
            return response()->json(['data'=>[],'status'=>500 ,'message'=>$e->getMessage()],500) ;
        }
    }

    public function destroy(Request $request)
    {
        try {
            $type = $request->type;
            $wishlistable_id = $request->ref_id;
            $wishlistable_type = WishlistType::from($type)->className();
            $request->merge(['wishlistable_type' => $wishlistable_type]);
            $request->merge(['wishlistable_id' => $wishlistable_id]);

            UserWishlist::where($request->only(['wine_user_id','wishlistable_id','wishlistable_type']))->delete();
            return response()->json(['data'=>[],'status'=>200 ,'message'=>__("Wishlist delete successfully.")],200) ;

        }catch (\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['data'=>[],'status'=>500 ,'message'=>$e->getMessage()],500) ;
        }
    }

}
