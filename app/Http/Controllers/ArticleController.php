<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $collection = (is_null($request->input('keyword'))) ? ArticleResource::collection(Article::all()) : ArticleResource::collection(Article::all()->keywords()->where('keyword', $request->input('keyword'))->get());

        // $keyword = $request->input('keyword');
        // $author = $request->input('author');
        // $date = $request->input('date');
        // $category = $request->input('category');
        // $source = $request->input('source');

        // $article_keyword = ArticleResource::collection(Article::where('keyword', $keyword)->get());
        // $article_author = ArticleResource::collection(Article::where('author', $author)->get());
        // $article_date = ArticleResource::collection(Article::where('date', $date)->get());
        // $article_category = ArticleResource::collection(Article::where('category', $category)->get());
        // $article_source = ArticleResource::collection(Article::where('source', $source)->get());
        // $article = ArticleResource::collection(Article::all());

        return $collection;
    }

    public function search(Request $request)
    {
        switch ($request) {
            case 'value':
                # code...
                break;

            case 'value':
                # code...
                break;

            default:
                # code...
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $article = Article::create($request->all());
        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     */
    public function showByKeyword(string $keyword)
    {
        $article = Article::where('keyword', $keyword);
        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     */
    public function showByDate(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     */
    public function showByCategory(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     */
    public function showByAuthor(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->update($request->all());
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return response(null, 204);
    }
}
