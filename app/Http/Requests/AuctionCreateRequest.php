<?php

namespace App\Http\Requests;

use App\Models\WineUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuctionCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::id()? WineUser::find(Auth::id()): 0;
        return $user && $user->canAuction();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $bottle_size = config('auction_attributes.bottle_size');
        return [
            "title" => ["required","min:5","max:255"],
            "description" => ["required"],
            "price" => ["required"],
            "is_publish" => ["required"],
            "type" => ["required"],
            "start_date" => ["required","date_format:Y-m-d H:i:s"],
            "end_date" => ["required_if:type,1","date_format:Y-m-d H:i:s"],
            'products' => 'required|array',
            "products.*.description" => 'required|string',
            "products.*.wine_name" => 'required|string',
            "products.*.region_id" => 'required',
            "products.*.producer_id" => 'required',
            "products.*.year" => 'required',
            "products.*.wine_maker" => 'required',
            "products.*.country_id" => 'required',
            "products.*.bottle_size" => ['required', Rule::in($bottle_size)],
            "products.*.wine_type" => 'required',
            "products.*.wine_tag" => 'required|array',
            "products.*.wine_variety" =>'required|array',
            "products.*.review_name" => 'required',
            "products.*.stock" => 'required',
            "products.*.volume" => 'required',
        ];
    }
}
