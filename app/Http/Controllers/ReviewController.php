<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\Fail;
use App\Enums\ReviewType;
use App\Http\Resources\ProductReviewResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\Success;
use App\Models\Auction;
use App\Models\AuctionPackage;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\WineUser;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request) {
        try {
            $data = $this->getReviewDetail($request);
            return  $data->response()->getData(false);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function getReviewDetail($request){
        $limit = $request->limit ?? config('constant.number_of_items_per_page');
        $reviewType = $request->reviewable_type;
        $reviewId = $request->reviewable_id;
        $query = Review::with('wineUser');
        if(!empty($reviewType)){
            $type = ReviewType::from($reviewType)->className();

            $query=$query->where('reviewable_type',$type);
            if(!empty($reviewId)){
                $query=$query->where('reviewable_id',$reviewId);
            }
        }
        $query = $query->orderByDesc('id')->paginate($limit);
        return ReviewResource::collection($query);
    }

    public function storeWineUserReview(ReviewRequest $request,WineUser $wineUser)
    {
        try {

            $review = $this->storeReview($wineUser,$request);
            return response()->json(['message' => "User Review store successfully.", "data" => new ReviewResource($review)], 201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
    public function storeProductReview(ReviewRequest $request,Product $product)
    {
        try {

            $review = $this->storeReview($product,$request);

            return response()->json(['message' => "Product Review store successfully.", "data" => new ReviewResource($review)], 201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function storeAuctionReview(ReviewRequest $request,Auction $auction)
    {
        try {

            $review = $this->storeReview($auction,$request);

            return response()->json(['message' => "Auction Review store successfully.", "data" => new ReviewResource($review)], 201);

        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function storeAuctionPackageReview(ReviewRequest $request,AuctionPackage $auctionPackage)
    {
        try {

            $review = $this->storeReview($auctionPackage,$request);

            return response()->json(['message' => "Auction Package Review store successfully.", "data" => new ReviewResource($review)], 201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    private function storeReview($reviewable, Request $request)
    {
        return $reviewable->reviews()->create($request->only("wine_user_id","rating","review_content"));

    }

    public function getProductReview(Request $request,Product $product)
    {
        try {
            return new ProductReviewResource($product);

        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
    public function deleteReview(Request $request,$id)
    {
        try {
            Review::where('id',$id)->delete();
            $data = $this->getReviewDetail($request);
            return response()->json(['message' => "Review deleted successfully.", "data" => $data], 201);

        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
    public function updateReview(ReviewRequest $request,$id)
    {
        try {
            Review::where('id',$id)->update([
                'rating'=>$request->rating,
                'review_content'=>$request->review_content,
            ]);
            $data = $this->getReviewDetail($request);
            return response()->json(['message' => "Review updated successfully.", "data" => $data], 201);

        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
}
