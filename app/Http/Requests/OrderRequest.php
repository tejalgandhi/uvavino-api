<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'items.*.type' => ['required', 'in:product,auction,auction_package'],
            'items.*.id' => ['required'],
            'items.*.amount' => ['required', 'numeric'],
            'items.*.qty' => ['required', 'numeric'],
        ];
    }
}
