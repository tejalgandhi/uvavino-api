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
        $user = Auth::id()? WineUser::find(Auth::id()): null;
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
            "is_private" => ["required"],
            "type" => ["required"],
            "max_price" => ["required"],
        ];
    }
}
