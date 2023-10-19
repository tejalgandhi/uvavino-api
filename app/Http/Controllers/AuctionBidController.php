<?php

namespace App\Http\Controllers;


use App\Http\Requests\AuctionBidRequest;
use App\Http\Requests\AutomaticBidRequest;
use App\Http\Resources\AuctionBidResource;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Models\Auction;
use App\Models\AuctionBid;
use App\Models\AutomaticBid;
use Illuminate\Support\Facades\Log;
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

    public function checkMin($auction,$amt)
    {
        $min = $auction->nextMinBid();
        if($amt < $min) {
            Log::info("Minimum bid is {$min}, so {$amt} not accepted !");
            return false;
        }
        return true;
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


    /**
     * @param AutomaticBidRequest $request
     * @param Auction $auction
     * @return \Illuminate\Http\JsonResponse
     */
    public function automaticBid(AutomaticBidRequest $request, Auction $auction)
    {
        $max = $request->amount;
        $wine_user_id = $request->wine_user_id;
        if ($max <= $auction->nextMinBid()) {
            return (new Fail(__('Invalid amount, amount must be higher than current bid.')))->json(400);
        }
        $auto_bid = AutomaticBid::updateorcreate([
            'auction_id' => $auction->id,
            'wine_user_id' => $wine_user_id],
          [
            'max_limit' => $max
        ]);
        if($auction->auto_bids()->count() > 0)
        {
            $this->evalWith($auto_bid,$auction);
        }
        else{
            $this->autoBid($auto_bid,$auction);
        }

        Log::info('EVENT');
        $current_bid = $auction->bidRaised();
        $next_bid = $auction->nextMinBid();
        $quick_bids = $auction->quickBid();
        event(new  \App\Events\BidRaised($auction->id, $current_bid, $next_bid, $quick_bids, $wine_user_id));
        return (new Success(__('Auction Automatic Bidder Create Successfully')))->json(201);
    }

    public function autoBid(AutomaticBid $automaticBid,Auction $auction)
    {
        if ($automaticBid) {
            if(!$auction->makeAutobid($automaticBid,$auction)) {
                $automaticBid = null;
            }
        }
    }

    public function evalWith(AutomaticBid $automaticBid,Auction $auction): AutomaticBid
    {
        // Check if the auction can be auto-bid with the current AutoBid
        if ($auction->makeAutobid($this)) {
            // If successful, recursively call evalWith on the opponent
            return $automaticBid->evalWith($this, $auction);
        }

        // If the auto-bid fails, return the opponent
        return $automaticBid;
    }


    public function next(Auction $auction)
    {
        $latest_bid = AuctionBid::where('auction_id',$auction->id)->latest()->first();
        $value = !empty($latest_bid) ? $latest_bid->value : $auction->baskets->price ;;
        return $value + Config('constant.minimum_bid_step');
    }

    public function setNewBid($user_id,$auction_id,$min)
    {
        AuctionBid::create([
            'auction_id' => $auction_id,
            'wine_user_id' => $user_id,
            'value' => $min
        ]);
    }

}
