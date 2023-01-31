<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\ProductResource;
use App\Http\Resources\Success;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function store(Request $request){
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

    public function get(Request $request){
        try {
            $wishlist =  Wishlist::where('wine_user_id',$request->wine_user_id)->first();
            if(!$wishlist){
                return (new Fail(__('Wishlist does not exist')))->json(404);
            }
            return $wishlist;
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function destroy(Request $request){
        try {
            $wishlist =  Wishlist::where('product_id',$request->product_id)->delete();
            return $wishlist;
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

}
