<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserAddressResource;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function index()
    {
        try {
            $address = UserAddress::where('wine_user_id',request()->user()->id)->get();
            return response()->json(['code' => 200, 'data' => UserAddressResource::collection($address)], 200);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }
    public function addAddress()
    {
        try {
            $addressArr = [
                'wine_user_id' => request()->user()->id,
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
            if (request()->has('id')) {
                $userAddress = UserAddress::whereId(request()->input('id'))->first();
                $userAddress->fill($addressArr)->save();
            }else{
                $userAddress = UserAddress::create($addressArr);
            }
            if(request()->input('is_default') == 1){
                UserAddress::where('wine_user_id',request()->user()->id)
                    ->where('id','!=',$userAddress->id)
                    ->update([
                        'is_default'=>0
                    ]);
            }

            return response()->json(['code' => 200, 'data' => new  UserAddressResource($userAddress)], 200);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request,$id)
    {
        try {
            UserAddress::where('id',$id)->delete();
            return response()->json(['code' => 200, 'message' => "Address Deleted Successfully"], 200);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

}
