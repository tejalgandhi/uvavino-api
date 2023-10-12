<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Models\AuctionPackage;
use Illuminate\Http\Request;
use App\Enums\OrderType;


class CartController extends Controller
{
    public function getCartItems(Request $request)
    {
        try {
            $data =  CartResource::collection($request->user()->carts()->with('items')->paginate(10));
            return response()->json(['message' => "Get Cart Items", "data" => $data], 201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function storeAuctionPackageCart(CartRequest $request,AuctionPackage $auctionPackage)
    {
        try {
            $this->storeCart($auctionPackage,OrderType::auction_package->className(),$request);

            return (new Success(__("Auction Package Added in the cart")))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
    private function storeCart($cartable, $type, Request $request)
    {
        $amount = $request->amount;
        $qty = $request->qty;
        $total_amount = $qty * $amount;

        $user = $request->user();
        $cart = $user->carts()->updateOrcreate(['wine_user_id'=>$request->wine_user_id]);

        return  $cart->items()->updateOrcreate([
            'cartable_type' => $type,
            'cartable_id' => $cartable->id,
            'cart_id' => $cart->id,
        ],[
            'amount' =>$amount,
            'total_amount' => $total_amount,
            'qty' => $qty
        ]);

    }
}
