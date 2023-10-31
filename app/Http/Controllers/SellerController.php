<?php

namespace App\Http\Controllers;

use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Http\Resources\WineUserResource;
use App\Models\WineUser;
use App\Services\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function connect(Request $request, Payment $payment) {
        if($url = $payment->connect($request->user())) {
            return (new Success(compact('url')))->json(200);
        }
        return (new Fail("Failed!"))->json(500);
    }


    public function getSellerInfo(Request $request,$id)
    {
        $user = WineUser::whereId($id)->first();
        return response()->json([
            'status' => 200,
            'user' => new WineUserResource($user),
        ]);
    }


}
