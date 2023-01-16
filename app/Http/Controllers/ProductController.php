<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\StaticPageResource;
use App\Models\Products;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return ProductResource::collection(Products::paginate(10));
    }

    public function show(Request $request, $id)
    {
        return Products::whereId($id)->first();
    }
}
