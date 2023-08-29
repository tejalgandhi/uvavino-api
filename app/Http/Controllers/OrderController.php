<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderShowRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\OrderResource;
use App\Http\Resources\Success;
use App\Models\Auction;
use App\Models\AuctionPackage;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

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
            $orderableClass =  match($item['orderable_type']){
                'product' => Product::class,
                'auction' => Auction::class,
                'auction_package' => AuctionPackage::class,
                default => Product::class,
            };
            $orderFor = $orderableClass::find($item['orderable_id']);
            if(!$orderFor){
                continue;
            }
             $order->items()->create([
                 'orderable_type' => $orderableClass,
                 'orderable_id' => $orderFor->id,
                 'amount' => $item['amount'],
                'qty' => $item['qty'],
            ]);

        }
        return (new Success(__('order.placed')))->json(201);
    }
}
