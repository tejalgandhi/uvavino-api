<?php

namespace App\Http\Requests;

use App\Http\Resources\Fail;
use App\Models\WineUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Enums\BottleSize;
use App\Enums\Medal;
use App\Enums\Year;
use App\Enums\AuctionType;
use App\Enums\Capacity;
use Illuminate\Auth\Access\AuthorizationException;


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
    protected function failedAuthorization()
    {
        $errorResponse = [
            'status' => 422,
            'error' => 'You are not authorized to create auction. Please purchase auction package.',
        ];

        throw new HttpResponseException(response()->json($errorResponse, 422));

        // You can also throw a custom exception:
        // throw new AccessDeniedHttpException('You are not authorized to access this area');
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "title" => ["required","min:5","max:255"],
            "description" => ["required"],
            "price" => ["required"],
            "is_publish" => ["required"],
            "type" => ["required",Rule::in(AuctionType::values())],
            "start_date" => ["required"],
            "end_date" => ["required_if:type,".AuctionType::TIMEBASED->value],
            "live_time_interval" => ["required_if:type,".AuctionType::LIVE->value,'integer'],
            'products' => 'required|array',
            "products.*.description" => 'required|string',
            "products.*.wine_name" => 'required|string',
            "products.*.region_id" => 'required',
            "products.*.producer_id" => 'required',
            "products.*.year" => ['required'],
            "products.*.wine_maker" => 'required',
            "products.*.country_id" => 'required',
            "products.*.bottle_size" => ['required', Rule::in(BottleSize::values())],
            "products.*.wine_type_id" => 'required',
            "products.*.wine_tag" => 'required|array',
            "products.*.wine_variety" =>'required|array',
            "products.*.basket_quantity" => 'required',
            "products.*.volume" => ['required'],
        ];
    }
}
