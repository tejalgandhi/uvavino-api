<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Models\AuctionPackage;
use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use App\Enums\OrderType;
use Stripe\PaymentIntent;
use Stripe\Stripe;


class CartController extends Controller
{
    public function getCartItems(Request $request)
    {
        try {
            return response()->json(['message' => "Get Cart Items", "data" => $this->getCartDetail()], 201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function getCartDetail(){
        return CartResource::collection(request()->user()->carts()->with('items')->get());
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
        $amount = $cartable->amount;
        $qty = $request->qty;
        $total_amount = $qty * $amount;

        $user = $request->user();
        $cart = $user->carts()->updateOrcreate(['wine_user_id'=>$request->wine_user_id]);

       $cartDetail = CartDetail::where([
            'cartable_type' => $type,
            'cartable_id' => $cartable->id,
            'cart_id' => $cart->id
        ])->first();

       if(!empty($cartDetail)){
           $qty = $cartDetail->qty + $qty;
           $total_amount = $qty * $amount;
       }

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

    public function update(CartRequest $request,$id)
    {
        try{
            $amount = $request->amount;
            $qty = $request->qty;
            $total_amount = $qty * $amount;

            CartDetail::where('id',$id)->update([
                'amount' =>$amount,
                'total_amount' => $total_amount,
                'qty' => $qty
            ]);

            return response()->json(['message'=>"Cart Item Updated successfully.","data"=>$this->getCartDetail()],201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function destroy(Request $request,$id)
    {
        try{
            $user = request()->user();
            CartDetail::where('id',$id)->delete();
            if ($user->carts()->has('items')->count() === 0) {
                // If the user's cart has no items, delete it
                $user->carts()->delete();
            }
            return response()->json(['message'=>"Cart Item deleted successfully.","data"=>$this->getCartDetail()],201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(config('payment.stripe.secret_key'));

        try {
            $intent = PaymentIntent::create([
                'amount' => $request->amount * 100,  // Amount in cents
                'currency' => $request->currency,
            ]);

            // Return the Payment Intent ID to your frontend for confirmation
            return response()->json(['client_secret' => $intent->client_secret]);
        } catch (\Stripe\Exception\CardException $e) {
            // Handle card error
            return (new Fail($e->getMessage()))->json();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Handle other Stripe-specific errors
            return (new Fail($e->getMessage()))->json();
        } catch (\Exception $e) {
            // Handle generic exceptions
            return (new Fail($e->getMessage()))->json();
        }
    }

}
