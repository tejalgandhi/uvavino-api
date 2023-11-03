<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderShowRequest;
use App\Http\Resources\CartDetailResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\Fail;
use App\Http\Resources\OrderResource;
use App\Http\Resources\Success;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Enums\OrderType;
use App\Models\WineUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request) {
        try {
            return response()->json(['message' => "Get Order Items", "data" => $this->getOrderDetail($request)], 201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function getOrderDetail($request){
        $orderType = $request->order_type;
        $query = request()->user()->orders()->with('items');
        if(!empty($orderType)){
            $type = OrderType::from($orderType)->className();

            $query=$query->whereHas('items',function ($q) use($type){
                $q->where('orderable_type',$type);
            });
        }
        $query = $query->orderByDesc('id')->paginate(10);
        return OrderResource::collection($query);
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
        $response = DB::transaction(function () use ($request) {
            $user = $request->user();
            $cart_items = $request->cart_items;
            $order_no = generateOrderNo();
            $order = $user->orders()->create(['address_id' => $request->address_id, 'order_no' => $order_no]);
            $cart_id = 0;
            $available_auctions = $user->available_auctions;

            foreach ($cart_items as $cart_item) {
                $item = $this->getCartDetail($cart_item);
                $cart_id = $item->cart_id;
                $total_amount = $item->amount * $item->qty;
                $order->items()->create([
                    'order_id' => $order->id,
                    'orderable_type' => $item->cartable_type,
                    'orderable_id' => $item->cartable_id,
                    'amount' => $item->amount,
                    'qty' => $item->qty,
                    'total_amount' => $total_amount,
                ]);

                if ($item->cartable_type == OrderType::auction_package->className())
                {
                    $qty = $item->cartable->qty * $item->qty;
                    $available_auctions = $available_auctions + $qty;
                    WineUser::where('id', $user->id)->update(['available_auctions' => $available_auctions]);
                }
            }

            CartDetail::where('cart_id', $cart_id)->delete();
            Cart::where('id', $cart_id)->delete();
            return [
                'msg' => __('order.placed'),
                'status' => true,
                'order' => $order,
            ];
        });
        return response()->json($response, Response::HTTP_OK);

    }

    public function getCartDetail($id)
    {
        return new CartDetailResource(CartDetail::find($id));

    }
}
