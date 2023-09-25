<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Http\Resources\Fail;
use App\Http\Resources\StaticPageResource;
use App\Models\AppVariable;
use App\Models\Country;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function index(Request $request)
    {
        return StaticPageResource::collection(StaticPage::paginate(10));
    }

    public function show(Request $request, $slug)
    {
        return StaticPage::whereSlug($slug)->first();
    }

    public function getAppVariables(Request $request)
    {
        $app_varibles = AppVariable::select('key','value');
        if($request->key){
            $app_varibles = $app_varibles->where('key',$request->key);
        }
        return $app_varibles->get();
    }


    public function showStaticPages(Request $request,$key)
    {
        return AppVariable::whereKey($key)->first();
    }

    public function countries(Request $request)
    {
        try {
            $data = Country::where('status',Country::IS_ACTIVE)->orderBy('name','asc')->get();
            return response()->json(['message' => "Get Countries", "data" => CountryResource::collection($data)], 201);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
}
