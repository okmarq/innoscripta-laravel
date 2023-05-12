<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewYorkTimesService
{
    protected $apiKey;
    protected $cacheTime = 3600; // Cache articles for 1 hour

    public function __construct()
    {
        $this->apiKey = config('services.nytimes.api_key');
    }

    public function getArticles($keyword, $date, $category, $source)
    {
        $cacheKey = 'nytimes_articles_' . $keyword . '_' . $date . '_' . $category . '_' . $source;
        return Cache::remember($cacheKey, $this->cacheTime, function () use ($keyword, $date, $category, $source) {
            $response = Http::get('https://api.nytimes.com/svc/search/v2/articlesearch.json', [
                'q' => $keyword,
                'begin_date' => $date,
                'fq' => "news_desk:({$category}) AND source:({$source})",
                'api-key' => $this->apiKey
            ]);
            return $response->json();
        });
    }
}
