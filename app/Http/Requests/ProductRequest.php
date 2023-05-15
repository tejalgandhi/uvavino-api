<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            "title" => ["required"],
            "stock" => ["required"],
            "price" => ["required"],
            "weight" => ["required"],
            "dimensions" => ["required"],
            "wine_name" => ["required"],
            "drink_type_id" => ["required"],
            "bottle_size" => ["required"],
            "country_id" => ["required"],
            "region_id" => ["required"],
            "producer_id" => ["required"],
            "wine_maker" => ["required"],
            "type_of_wine" => ["required"],
            "year" => ["required"],
            "grape_varieties" => ["required"],
            "wine_tag_id" => ["required"]
        ];
    }
}
