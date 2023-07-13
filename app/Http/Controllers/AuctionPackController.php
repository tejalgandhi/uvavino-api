<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionPackRequest;
use App\Http\Resources\AuctionPackResource;
use App\Models\AuctionPackage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuctionPackController extends Controller
{
    public function index(Request $request)
    {
        $order_by = $request->order_by;
        $query = AuctionPackage::query();
        if($order_by == 'bestseller'){
            $query->where('bestsellers',AuctionPackage::IS_BESTSELLER);
        }
        $query = match($order_by){
        'ascending' => $query->orderBy('auction_packages.name', 'asc'),
            'descending' => $query->orderBy('auction_packages.name', 'desc'),
            'recent' => $query->orderBy('auction_packages.created_at', 'desc'),
            'older' => $query->orderBy('auction_packages.created_at', 'asc'),
            'expensive' => $query->orderBy('auction_packages.amount', 'desc'),
            'cheapest' => $query->orderBy('auction_packages.amount', 'asc'),
            'discount' => $query->orderBy('auction_packages.discount_amount', 'desc'),
            'bestseller' => $query->orderBy('bestsellers_order', 'asc'),
            default => $query
        };
        return AuctionPackResource::collection(
            $query->select(
                AuctionPackResource::selectLst()
            )->paginate(AuctionPackage::NUMBER_OF_ITEMS_PER_PAGE)
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
