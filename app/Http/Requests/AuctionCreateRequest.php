<?php

namespace App\Http\Requests;

use App\Models\WineUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
        return [
            "title" => ["required"],
            "description" => ["required"],
            "is_publish" => ["required"],
            "type" => ["required"],
            "max_price" => ["required"],
            "start_date" => ["required","date_format:Y-m-d H:i:s"],
            "end_date" => ["required_if:type,1","date_format:Y-m-d H:i:s"],
            'basket.title' => 'required|min:5|max:255',
            'basket.stock' => 'required',
            'basket.price' => 'required',
            'basket.weight' => 'required',
            'products' => 'required|array',
            "products.*.title" => 'required|string',
            "products.*.description" => 'required|string',
            "products.*.wine_name" => 'required|string',
            "products.*.region_id" => 'required',
            "products.*.producer_id" => 'required',
            "products.*.year" => 'required',
            "products.*.wine_maker" => 'required',
            "products.*.country_id" => 'required',
            "products.*.bottle_size" => 'required',
            "products.*.drink_type_id" => 'required',
            "products.*.type_of_wine" => 'required',
            "products.*.wine_tag_id" => 'required',
            "products.*.grape_̥varieties" => 'required',
            "products.*.review_name" => 'required',
            "products.*.basket_quantity" => 'required',
            "products.*.unit_quantity" => 'required',
            "products.*.unit" => 'required',
        ];
    }
}
