<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquiryRequest;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    public function store(InquiryRequest $request)
    {
        try {
            $contactUs = Inquiry::create($request->all());
            $contactUs->save();

            if ($request->hasFile('file')) {
                $contactUs->uploadImages('file', $request->file, ['inquiry']);
            }

            return response()->json(['data'=>$contactUs,'status'=>201 ,'message'=>__('Inquiry form submitted successfully.')],201) ;

        }catch (\Exception $e){

            Log::info($e->getMessage());
            return response()->json(['data'=>[],'status'=>500 ,'message'=>$e->getMessage()],500) ;
        }
    }

}
