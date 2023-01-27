<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\Fail;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\Success;
use App\Models\Review;
use Illuminate\Http\Request;
class ReviewController extends Controller
{
    public function index(Request $request)
    {
        //category => product
        //category_id => product_id
        $category = $request->category;
        $category_id = $request->category_id;
        $review =  Review::Query();
        if(!empty($category) && !empty($category_id)) {
            $review = $review->where('ref_type', $category)
                ->where('ref_id', $category_id);
        }
        $review = $review->paginate(10);
        return ReviewResource::collection($review);
    }

    public function store(ReviewRequest $request)
    {
        try {
            Review::create($request->only([
                "ref_type",
                "ref_id",
                "user_id",
                "rating",
                "review_content",
            ]));
            return (new Success(__("Review store successfully.")))->json();
        }catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }

    }

}
