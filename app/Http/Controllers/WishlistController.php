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
    public function index(Request $request)
    {
        return WishlistResource::collection($request->user()->wishlist);
    }

    public function store(WishlistRequest $request)
    {
        Wishlist::create($request->only([
            "wine_user_id",
            "product_id"
        ]));
        return (new Success(__("Wishlist store successfully.")))->json(201);
    }

    public function destroy(Request $request)
    {
        Wishlist::where($request->only(['wine_user_id','product_id']))->delete();
        return (new Success(__("Product delete successfully.")))->json();
    }

}
