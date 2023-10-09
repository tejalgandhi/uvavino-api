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

        ];
    }
}
