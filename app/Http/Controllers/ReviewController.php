<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\Success;
use App\Models\Review;
use App\Enums\ReviewType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        //category => product
        //category_id => product_id
        $category = $request->type;
        $category_id = $request->id;
        $review =  Review::with('wineUser');
        if(!empty($category) && !empty($category_id)) {
            $cat = match($request->type){
                'product' => ReviewType::Product,
                'auction' => ReviewType::Auction,
                'auction_package' => ReviewType::AuctionPack,
                default => ReviewType::Product
            };
            $review = $review->where('reviewable_type', $cat)
                ->where('reviewable_id', $category_id);
        }
        $review = $review->paginate(10);
        return ReviewResource::collection($review);
    }

    public function store(ReviewRequest $request)
    {
        try {
            $type =  match($request->type){
            'product' => ReviewType::Product,
                'auction' => ReviewType::Auction,
                'auction_package' => ReviewType::AuctionPack,
                default => ReviewType::Product
                };
            $check = $this->checkValueExist($type->value,$request->id);
            if(!$check){
                return (new Fail("Data not found in the DB."))->json();
            }
            Review::create([
               "reviewable_type" => match($request->type){
               'product' => ReviewType::Product,
                'auction' => ReviewType::Auction,
                'auction_package' => ReviewType::AuctionPack,
                default => ReviewType::Product
                },
                "reviewable_id" => $request->id,
                "wine_user_id" => $request->wine_user_id,
                "rating" => $request->rating,
                "review_content" => $request->review_content,
            ]);
            return (new Success(__("Review store successfully.")))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }

    }

    public function checkValueExist($model,$id){
        return ($model::where('id',$id)->count()) > 0 ? true : false;
    }

}
