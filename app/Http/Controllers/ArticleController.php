<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Models\Preference;
use App\Services\NewsApiService;
use App\Services\NewYorkTimesService;
use App\Services\TheGuardianService;

class ArticleController extends Controller
{

    protected $newsApiService;
    protected $theGuardianService;
    protected $newYorkTimesService;
    protected $cacheTime = 3600; // Cache articles for 1 hour

    public function __construct(NewsApiService $newsApiService, TheGuardianService $theGuardianService, NewYorkTimesService $newYorkTimesService)
    {
        $this->newsApiService = $newsApiService;
        $this->theGuardianService = $theGuardianService;
        $this->newYorkTimesService = $newYorkTimesService;
    }

    public function getArticles(string $keyword)
    {
        $cacheKey = 'articles_' . $keyword;

        return Cache::remember($cacheKey, $this->cacheTime, function () use ($keyword) {
            // Fetch articles from all the news sources
            $newsApiArticles = $this->newsApiService->getArticles($keyword);
            $guardianArticles = $this->theGuardianService->getArticles($keyword);
            $nyTimesArticles = $this->newYorkTimesService->getArticles($keyword);

            // Merge the articles from all the sources
            $articles = array_merge($newsApiArticles, $guardianArticles, $nyTimesArticles);

            // Save articles to database
            Article::insert($articles);
        });
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $date = $request->input('date');
        $category = $request->input('category');
        $source = $request->input('source');

        $this->getArticles($keyword);

        if (!is_null($date) || !is_null($category) || !is_null($source)) {
            $cacheKey = 'search_' . $date . '_' . $category . '_' . $source . '_' . $keyword;
            return Cache::remember($cacheKey, $this->cacheTime, function () use ($keyword, $date, $category, $source) {
                return ArticleResource::collection(
                    Article::where('title', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%")
                        ->where('published_at', '>=', Carbon::parse($date)->format('Y-m-d H:i:s'))
                        ->where('category', 'LIKE', "%{$category}%")
                        ->where('source', 'LIKE', "%{$source}%")
                        ->get()
                );
            });
        } else {
            $cacheKey = 'search_' . $keyword;
            return Cache::remember($cacheKey, $this->cacheTime, function () use ($keyword) {
                return ArticleResource::collection(
                    Article::where('title', 'LIKE', "%{$keyword}%")
                        ->orWwhere('description', 'LIKE', "%{$keyword}%")
                        ->get()
                );
            });
        }
    }

    public function index()
    {
        $user = auth()->user();

        // get user preference
        $preference = Preference::where('user_id', $user->id)->orderBy('id', 'desc')->first();

        $source = $preference->source ?? null;
        $category = $preference->category ?? null;
        $author = $preference->author ?? null;

        // get articles based on user preferences
        $cacheKey = 'preferred_' . $author . '_' . $category . '_' . $source;
        return Cache::remember($cacheKey, $this->cacheTime, function () use ($category, $source, $author) {
            return ArticleResource::collection(
                Article::where('category', 'LIKE', "%{$category}%")
                    ->orWhere('source', 'LIKE', "%{$source}%")
                    ->orWhere('author', 'LIKE', "%{$author}%")
                    ->get()
            );
        });
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
