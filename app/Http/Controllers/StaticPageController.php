<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaticPageResource;
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
}
