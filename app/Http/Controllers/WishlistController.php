<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request){
        try {
            $wishlist =  $request->user()->wishlist;
            if(count($wishlist) == 0){
                return (new Fail(__('Wishlist does not exist')))->json(404);
            }
            return WishlistResource::collection($wishlist);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function store(WishlistRequest $request){
        try {
            Wishlist::create($request->only([
                "wine_user_id",
                "product_id"
            ]));
            return (new Success(__("Wishlist store successfully.")))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function destroy(Request $request){
        try {
             Wishlist::where('wine_user_id',$request->wine_user_id)->where('product_id',$request->product_id)->delete();
            return (new Success(__("Product delete successfully.")))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

}
