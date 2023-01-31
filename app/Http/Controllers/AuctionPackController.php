<?php

namespace App\Http\Controllers;

use App\Models\AuctionPackage;
use Illuminate\Http\Request;

class AuctionPackController extends Controller
{
    public function index()
    {
        return AuctionPackage::all();
    }
}
