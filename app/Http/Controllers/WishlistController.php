<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Http\Resources\WishlistResource;
use App\Models\Basket;
use App\Models\WineUser;
use App\Models\Wishlist as UserWishlist;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlist = Wishlist::where('wine_user_id',Auth::id())->get();
        return WishlistResource::collection($wishlist);
    }

    public function store(WishlistRequest $request)
    {
        try {
            $wishlist = UserWishlist::where($request->only(['wine_user_id', 'basket_id']))->count();
            if ($wishlist > 0) {
                return response()->json(['data'=>[],'status'=>200 ,'message'=>__('Product already exists')],200) ;
            }
            $basket = Basket::where('id',$request->basket_id)->count();
            if ($basket == 0) {
                return response()->json(['data'=>[],'status'=>200 ,'message'=>__('Product not found.')],200) ;
            }
            UserWishlist::create($request->only([
                "wine_user_id",
                "basket_id"
            ]));
            return response()->json(['data'=>[],'status'=>201 ,'message'=>__('Wishlist store successfully.')],201) ;

        }catch (\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['data'=>[],'status'=>500 ,'message'=>$e->getMessage()],500) ;
        }
    }

    public function destroy(Request $request,$basket_id)
    {
        try {

            UserWishlist::where($request->only(['wine_user_id','basket_id']))->delete();
            return response()->json(['data'=>[],'status'=>200 ,'message'=>__("Wishlist delete successfully.")],200) ;

        }catch (\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['data'=>[],'status'=>500 ,'message'=>$e->getMessage()],500) ;
        }
    }

}
