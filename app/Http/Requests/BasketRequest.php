<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\BottleSize;
use App\Enums\Medal;
use App\Enums\Year;
use App\Enums\AuctionType;
use App\Enums\Capacity;

class BasketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5|max:255',
            'stock' => 'required',
            'price' => 'required',
            'weight' => 'required',
            'products' => 'required|array',
            "products.*.description" => 'required|string',
            "products.*.wine_name" => 'required|string',
            "products.*.region_id" => 'required',
            "products.*.producer_id" => 'required',
            "products.*.year" => ['required', Rule::in(Year::values())],
            "products.*.wine_maker" => 'required',
            "products.*.country_id" => 'required',
            "products.*.bottle_size" => ['required', Rule::in(BottleSize::values())],
            "products.*.medal" => [Rule::in(Medal::values())],
            "products.*.wine_type_id" => 'required',
            "products.*.wine_tag" => 'required|array',
            "products.*.wine_variety" =>'required|array',
            "products.*.review_name" => 'required',
            "products.*.basket_quantity" => 'required',
            "products.*.volume" => ['required'],
        ];
    }
}
