<?php

namespace App\Services;

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

    public function getArticles($keyword, $date, $category, $source)
    {
        $cacheKey = 'newsapi_articles_' . $keyword . '_' . $date . '_' . $category . '_' . $source;

        return Cache::remember($cacheKey, $this->cacheTime, function () use ($keyword, $date, $category, $source) {
            $response = Http::withHeaders([
                'X-Api-Key' => $this->apiKey
            ])->get('https://newsapi.org/v2/everything', [
                'q' => $keyword,
                'sources' => $source,
                'from' => $date,
                'category' => $category
            ]);
            return $response->json();
        });
    }
}
