<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    public function store(ContactUsRequest $request)
    {
        try {
            $contactUs = ContactUs::create($request->all());
            $contactUs->save();

            if ($request->hasFile('file')) {
                $contactUs->uploadImages('file', $request->file, ['contact_us']);
            }



            return response()->json(['data'=>[],'status'=>201 ,'message'=>__('Contact us form submitted successfully.')],201) ;

        }catch (\Exception $e){

            Log::info($e->getMessage());
            return response()->json(['data'=>[],'status'=>500 ,'message'=>$e->getMessage()],500) ;
        }
    }

}
