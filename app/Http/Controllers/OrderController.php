<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderShowRequest;
use App\Http\Resources\CartDetailResource;
use App\Http\Resources\Fail;
use App\Http\Resources\OrderResource;
use App\Http\Resources\Success;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Enums\OrderType;
use App\Models\WineUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request) {
        return OrderResource::collection($request->user()->orders()->with('items')->paginate(10));
    }

    public function show(Request $request, Order $order)
    {
        if($request->wine_user_id == $order->wine_user->id) {
            return new OrderResource($order);
        }
        return (new Fail(__('Unauthorized')))->json(403);
    }

    public function store(OrderRequest $request)
    {
        $user = $request->user();
        $cart_items = $request->cart_items;
        $order = $user->orders()->create();
        $cart_id = 0;
        $available_auctions = $user->available_auctions;

        foreach ($cart_items as $cart_item)
        {
            $item = $this->getCartDetail($cart_item);
            $cart_id = $item->cart_id;
            $order->items()->create([
                'order_id' => $order->id,
                'orderable_type' => $item->cartable_type,
                'orderable_id' => $item->cartable_id,
                'amount' => $item->amount,
                'qty' => $item->qty,
            ]);

            if($item->cartable_type == OrderType::auction_package->className())
            {
                  $qty = $item->cartable->qty * $item->qty;
                  $available_auctions = $available_auctions + $qty;
                  WineUser::where('id',$user->id)->update(['available_auctions'=>$available_auctions]);
            }
        }

        CartDetail::where('cart_id',$cart_id)->delete();
        Cart::where('id',$cart_id)->delete();

        return (new Success(__('order.placed')))->json(201);
    }

    public function getCartDetail($id)
    {
        return new CartDetailResource(CartDetail::find($id));

    }
}
