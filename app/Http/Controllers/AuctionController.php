<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionCreateRequest;
use App\Http\Resources\Success;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index()
    {
        return "Need to implement!!";
    }

    public function create(AuctionCreateRequest $request)
    {
        $auction = Auction::create($request->only([

        ]));
        return (new Success(__('Auction Create Successfully'))).json(201);
    }

    public function show()
    {
        return "Need to implement!!";
    }

    public function bid()
    {
        return "Need to implement!!";
    }
}
