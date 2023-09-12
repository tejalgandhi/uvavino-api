<?php

namespace App\Http\Controllers;


use App\Http\Resources\AuctionBidResource;
use App\Http\Resources\Fail;
use App\Models\Auction;
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

}
