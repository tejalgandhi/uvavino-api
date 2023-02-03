<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionBidRequest;
use App\Http\Requests\AuctionCreateRequest;
use App\Http\Requests\AutomaticBidRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Models\Auction;
use App\Models\AuctionBid;
use App\Models\AutomaticBid;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index()
    {
        return Auction::paginate(10);
    }

    public function create(AuctionCreateRequest $request)
    {
        $auction = Auction::create($request->only([
            "lot_type",
            "title",
            "description",
            "is_private",
            "auction_type",
            "max_price",
            "invitations",
        ]));
        return (new Success(__('Auction Create Successfully')))->json(201);
    }

    public function show(Auction $auction)
    {
        return $auction;
    }

    public function bid(AuctionBidRequest $request, Auction $auction)
    {
        // Check if value is valid or not.
        $value = $request->value;
        if(!$auction->canBid($value)) {
            return (new Fail(__('Could not bid with ' . $value)));
        }
        AuctionBid::create([
            'auction_id' => $auction->id,
            'wine_user_id' => $request->wine_user_id,
            'value' => $request->value
        ]);
        return (new Success(__('Auction Bit Create Successfully')))->json(201);
    }

    public function automaticBid(AutomaticBidRequest $request, Auction $auction)
    {
        $max = $request->max_amount;
        if ($auction->max_price != null && $max > $auction->max_price) {
            return (new Fail(__('You should buy without auction in that price.')))->json(400);
        }
        if ($max < $auction->nextMinBid()) {
            return (new Fail(__('Your auto bid amount is too low.')))->json(400);
        }
        AutomaticBid::create([
            'auction_id' => $auction->id,
            'wine_user_id' => $request->wine_user_id,
            'max_amount' => $request->max_amount
        ]);
    }
}
