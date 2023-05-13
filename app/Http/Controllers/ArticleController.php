<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Services\NewsApiService;
use App\Services\NewYorkTimesService;
use App\Services\TheGuardianService;

class ArticleController extends Controller
{

    protected $newsApiService;
    protected $theGuardianService;
    protected $newYorkTimesService;

    public function __construct(NewsApiService $newsApiService, TheGuardianService $theGuardianService, NewYorkTimesService $newYorkTimesService)
    {
        $this->newsApiService = $newsApiService;
        $this->theGuardianService = $theGuardianService;
        $this->newYorkTimesService = $newYorkTimesService;
    }

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $date = $request->input('date');
        $category = $request->input('category');
        $source = $request->input('source');

        // Fetch articles from all the news sources
        $newsApiArticles = $this->newsApiService->getArticles($keyword);
        $guardianArticles = $this->theGuardianService->getArticles($keyword);
        $nyTimesArticles = $this->newYorkTimesService->getArticles($keyword);

        // Merge the articles from all the sources
        $articles = array_merge($newsApiArticles, $guardianArticles, $nyTimesArticles);

        Article::insert($articles);
    }

    public function getArticles()
    {
        // acquire articles from all sources
        // clean data
        // save to database
        // save keyword to database and attach article to it
        // allow users to search for article by keyword
        // allow users to filter articles by author, category and source
    }

    public function index()
    {
        return ArticleResource::collection(Article::all());
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
