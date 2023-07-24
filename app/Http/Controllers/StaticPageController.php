<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaticPageResource;
use App\Models\AppVariable;
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
}
