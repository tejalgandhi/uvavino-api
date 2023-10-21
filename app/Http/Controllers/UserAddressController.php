<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;

class UserAddressController extends Controller
{
    public function addAddress()
    {
        try {
            $addressArr = [
                'wine_user_id' => request()->user()->id,
                'company_name' => request()->input('company_name'),
                'nif' => request()->input('nif'),
                'country_code' => request()->input('country_code'),
                'country' => request()->input('country'),
                'address_first_line' => request()->input('address_first_line'),
                'address_second_line' => request()->input('address_second_line'),
                'city' => request()->input('city'),
                'state' => request()->input('state'),
                'post_code' => request()->input('post_code'),
                'phone_no' => request()->input('phone_no'),
                'email' => request()->input('email'),
                'dial_code' => request()->input('dial_code'),
                'iso_alpha2' => request()->input('iso_alpha2'),
                'is_default' => request()->input('is_default'),
            ];

            $address = UserAddress::create($addressArr);
            return response()->json(['code' => 200, 'data' => $address], 200);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

}
