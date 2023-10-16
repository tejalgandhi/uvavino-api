<?php

namespace App\Http\Controllers;


use App\Http\Requests\AuctionBidRequest;
use App\Http\Resources\AuctionBidResource;
use App\Http\Resources\Fail;
use App\Models\Auction;
use App\Models\AuctionBid;
use Illuminate\Support\Facades\Request;

/**
 * Class AuctionController
 * @package App\Http\Controllers
 */
class AuctionBidController extends Controller
{

    public function getAuctionHistory(Request $request, Auction $auction)
    {
        try {

            return AuctionBidResource::collection($auction->bids);

        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }


    /**
     * @param AuctionBidRequest $request
     * @param Auction $auction
     * @return Fail|\Illuminate\Http\JsonResponse
     */
    public function storeAuctionBid(AuctionBidRequest $request, Auction $auction)
    {
        try {
            // Check if value is valid or not.
            $value = $request->price;
            if (!$auction->canBid($value)) {
                return (new Fail(__('Could not bid with ' . $value)));
            }
            $data = AuctionBid::create([
                'auction_id' => $auction->id,
                'wine_user_id' => $request->wine_user_id,
                'value' => $value
            ]);

            return response()->json(['message' => "Auction Bid Create Successfully", "data" => $data], 201);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function endAuction(Auction $auction){
        try {

            $auction_bid = AuctionBid::latest()->first();
            $winner_bid = !empty($auction_bid)?$auction_bid->id:0;

            Auction::where('id',$auction->id)->update([
                'winner_bid'=>$winner_bid
            ]);

            $current_bid = $auction->bidRaised();
            $next_bid = $auction->nextMinBid();
            $quick_bids = $auction->quickBid();
            event(new  \App\Events\AuctionEnded($auction->id, $current_bid, $next_bid, $quick_bids));

            return response()->json(['message' => "Auction end Successfully", "data" =>[] ], 201);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

}
