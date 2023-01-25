<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();
        $articles = $query->paginate(10);
        return ArticleResource::collection($articles);
    }

    public function show($slug)
    {
        $article =  Article::whereSlug($slug)->first();
        if(!$article){
            return (new Fail(__('Article does not exist')))->json(404);
        }
        return $article;
    }

}
