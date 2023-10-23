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
            return response()->json(['message' => "Get Review Items", "data" => $this->getReviewDetail($request)], 201);
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function getReviewDetail($request){
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
        $query = $query->orderByDesc('id')->paginate(10);
        return ReviewResource::collection($query);
    }
//    public function index(Request $request)
//    {
//        //category => product
//        //category_id => product_id
//        $reviewable_type = $request->reviewable_type;
//        $reviewable_id = $request->reviewable_id;
//        $review =  Review::with('wineUser');
//        if(!empty($reviewable_type) && !empty($reviewable_id)) {
//            $reviewableClass =  match($request->reviewable_type){
//                'wine_user' => WineUser::class,
//                'product' => Product::class,
//                'auction' => Auction::class,
//                'auction_package' => AuctionPackage::class,
//                default => Product::class,
//            };
//
//            $review = $review->where('reviewable_type', $reviewableClass)
//                ->where('reviewable_id', $reviewable_id);
//        }
//        $review = $review->paginate(10);
//        return ReviewResource::collection($review);
//    }

    public function storeWineUserReview(ReviewRequest $request,WineUser $wineUser)
    {
        try {

            $this->storeReview($wineUser,$request);

            return (new Success(__("User Review store successfully.")))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
    public function storeProductReview(ReviewRequest $request,Product $product)
    {
        try {

            $this->storeReview($product,$request);

            return (new Success(__("Product Review store successfully.")))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function storeAuctionReview(ReviewRequest $request,Auction $auction)
    {
        try {

            $this->storeReview($auction,$request);

            return (new Success(__("Auction Review store successfully.")))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }

    public function storeAuctionPackageReview(ReviewRequest $request,AuctionPackage $auctionPackage)
    {
        try {

            $this->storeReview($auctionPackage,$request);

            return (new Success(__("Auction Package Review store successfully.")))->json();
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
}
