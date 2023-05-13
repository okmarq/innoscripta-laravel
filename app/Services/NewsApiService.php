<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsApiService
{
    protected $apiKey;
    protected $cacheTime = 3600; // Cache articles for 1 hour

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.api_key');
    }

    public function getArticles($keyword)
    {
        $cacheKey = 'newsapi_articles_' . $keyword;

        $articles = Cache::remember($cacheKey, $this->cacheTime, function () use ($keyword) {
            $response = Http::withHeaders([
                'X-Api-Key' => $this->apiKey
            ])->get('https://newsapi.org/v2/everything', [
                'q' => $keyword
            ]);
            return $response->json();
        });
        return $this->cleanArticles($articles);
    }

    public function cleanArticles(array $articles)
    {
        $articles_to_db = [];
        $index = 0;

        if ($articles['status'] === 'ok') {
            foreach ($articles['articles'] as $article) {
                $index++;
                $articles_to_db[$index]['title'] = $article['title'];
                $articles_to_db[$index]['content'] = $article['content'];
                $articles_to_db[$index]['description'] = $article['description'];
                $articles_to_db[$index]['url'] = $article['url'];
                $articles_to_db[$index]['url_to_image'] = $article['urlToImage'];
                $articles_to_db[$index]['published_at'] = Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s');
                $articles_to_db[$index]['source'] = $article['source']['name'];
                $articles_to_db[$index]['author'] = $article['author'];
                $articles_to_db[$index]['category'] = null;
            }
            return $articles_to_db;
        }
    }
}
