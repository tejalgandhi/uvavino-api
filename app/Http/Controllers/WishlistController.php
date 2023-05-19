<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Http\Resources\WishlistResource;
use App\Models\WineUser;
use App\Models\Wishlist as UserWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $user = WineUser::find(Auth::id());
        return WishlistResource::collection($user->wishlist);
    }

    public function store(WishlistRequest $request)
    {
        try {
            $wishlist = UserWishlist::where($request->only(['wine_user_id', 'basket_id']))->count();
            if ($wishlist > 0) {
                return (new Success(__("Product already exists")))->json(200);
            }
            UserWishlist::create([
                'wine_user_id'=>$request->only('wine_user_id'),
                'basket_id'=>$request->only('basket_id')
            ]);
            return (new Success(__("Wishlist store successfully.")))->json(201);
        }catch (\Exception $e){
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }

    public function destroy(Request $request,$basket_id)
    {
        UserWishlist::where($request->only(['wine_user_id','basket_id']))->delete();
        return (new Success(__("Product delete successfully.")))->json();
    }

}
