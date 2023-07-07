<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionPackRequest;
use App\Http\Resources\AuctionPackResource;
use App\Models\AuctionPackage;
use Illuminate\Support\Str;

class AuctionPackController extends Controller
{
    public function index()
    {
        return AuctionPackResource::collection(
            AuctionPackage::latest()->paginate(10)
        );
    }

    /**
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|object|null
     */
    public function show($slug)
    {
        $auctionPack = AuctionPackage::query()
            ->where('slug', $slug)
            ->first();
        if (!$auctionPack) {
            return response()->json(['data'=>[],'status'=>400 ,'message'=> __('Auction Pack does not exist')],400) ;
        }
        return response()->json(['data'=>$auctionPack,'status'=>200 ,'message'=> 'Auction get successfully.']) ;
    }


    public function store(AuctionPackRequest $request)
    {
        try {
            $request->request->add(['slug' => Str::slug($request->name)]);
            AuctionPackage::create(
                $request->only([
                    "name",
                    "qty",
                    "slug",
                    "amount",
               ]));
            return response()->json(['data'=>[],'status'=>201 ,'message'=>__('Auction Pack Create Successfully')],201) ;


        } catch (\Exception $e) {
            return response()->json(['data'=>[],'status'=>500 ,'message'=>$e->getMessage()],500) ;

        }

    }
}
