<?php

namespace App\Http\Controllers;

use App\Enums\OrderableType;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderShowRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\OrderResource;
use App\Http\Resources\Success;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $order = $user->orders()->create();
        foreach($request->items as $item) {
            $order->items()->create([
                'orderable_type' => match($item['type']) {
                    'product' => OrderableType::Product,
                    'auction' =>  OrderableType::Auction,
                    'auction_package' => OrderableType::AuctionPack,
                    default => OrderableType::Product,
                },
                'orderable_id' => $item['id'],
                'amount' => $item['amount'],
                'qty' => $item['qty'],
            ]);
        }
        return (new Success(__('order.placed')))->json(201);
    }
}
