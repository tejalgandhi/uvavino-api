<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Http\Resources\WishlistResource;
use App\Models\WineUser;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $user = WineUser::find(Auth::id());
        return WishlistResource::collection($user->wishlist);
    }

    public function store(WishlistRequest $request)
    {
        $wishlist = Wishlist::where($request->only(['wine_user_id','product_id']))->count();
        if($wishlist > 0){
            return (new Success(__("Product already exists")))->json(200);
        }
        Wishlist::create($request->only([
            "wine_user_id",
            "product_id"
        ]));
        return (new Success(__("Wishlist store successfully.")))->json(201);
    }

    public function destroy(Request $request,$product_id)
    {
        Wishlist::where($request->only(['wine_user_id','product_id']))->delete();
        return (new Success(__("Product delete successfully.")))->json();
    }

}
