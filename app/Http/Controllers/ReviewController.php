<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\Success;
use App\Models\Auction;
use App\Models\AuctionPackage;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        //category => product
        //category_id => product_id
        $reviewable_type = $request->reviewable_type;
        $reviewable_id = $request->reviewable_id;
        $review =  Review::with('wineUser');
        if(!empty($reviewable_type) && !empty($reviewable_id)) {
            $reviewableClass =  match($request->reviewable_type){
                'product' => Product::class,
                'auction' => Auction::class,
                'auction_package' => AuctionPackage::class,
                default => Product::class,
            };

            $review = $review->where('reviewable_type', $reviewableClass)
                ->where('reviewable_id', $reviewable_id);
        }
        $review = $review->paginate(10);
        return ReviewResource::collection($review);
    }

    public function store(ReviewRequest $request)
    {
        try {
            $reviewableClass =  match($request->reviewable_type){
                'product' => Product::class,
                'auction' => Auction::class,
                'auction_package' => AuctionPackage::class,
            };

            $reviewFor = $reviewableClass::find($request->reviewable_id);
            if(!$reviewFor){
                return (new Fail("Data not found in the DB."))->json();
            }
            $reviewFor->reviews()->create([
                "wine_user_id" => $request->wine_user_id,
                "rating" => $request->rating,
                "review_content" => $request->review_content
            ]);

            return (new Success(__("Review store successfully.")))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }

    }

}
