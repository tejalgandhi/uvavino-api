<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\Success;
use App\Models\Review;
use App\Enums\ReviewType;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        //category => product
        //category_id => product_id
        $category = $request->type;
        $category_id = $request->id;
        $review =  Review::with(['wine_user']);
        if(!empty($category) && !empty($category_id)) {
            $cat = match($request->type){
             'product' => ReviewType::Product
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
            Review::create([
               "reviewable_type" => match($request->type){
                    'product' => ReviewType::Product
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

}
