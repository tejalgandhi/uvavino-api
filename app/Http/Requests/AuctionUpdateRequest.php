<?php

namespace App\Http\Requests;

use App\Models\WineUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Enums\BottleSize;
use App\Enums\Medal;
use App\Enums\Year;
use App\Enums\AuctionType;
use App\Enums\Capacity;

class AuctionUpdateRequest extends FormRequest
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
            "title" => ["nullable","min:5","max:255"],
            "description" => ["nullable"],
            "price" => ["nullable"],
            "is_publish" => ["nullable"],
            "type" => ["nullable",Rule::in(AuctionType::values())],
            "start_date" => ["nullable","date_format:Y-m-d H:i:s"],
            "end_date" => ["required_if:type,".AuctionType::TIMEBASED->value,"date_format:Y-m-d H:i:s"],
            'products' => 'nullable|array',
            "products.*.description" => 'nullable|string',
            "products.*.wine_name" => 'nullable|string',
            "products.*.region_id" => 'nullable',
            "products.*.producer_id" => 'nullable',
            "products.*.year" => ['nullable'],
            "products.*.wine_maker" => 'nullable',
            "products.*.country_id" => 'nullable',
            "products.*.bottle_size" => ['nullable', Rule::in(BottleSize::values())],
            "products.*.wine_type_id" => 'nullable',
            "products.*.wine_tag" => 'nullable|array',
            "products.*.wine_variety" =>'nullable|array',
            "products.*.basket_quantity" => 'nullable',
            "products.*.volume" => ['nullable'],
        ];
    }
}
