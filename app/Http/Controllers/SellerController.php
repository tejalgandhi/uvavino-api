<?php

namespace App\Http\Controllers;

use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Services\Payment;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function connect(Request $request, Payment $payment) {
        if($url = $payment->connect($request->user())) {
            return (new Success(compact('url')))->json(200);
        }
        return (new Fail("Failed!"))->json(500);
    }
}
