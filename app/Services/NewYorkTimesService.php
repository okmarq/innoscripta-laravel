<?php

namespace App\Services;

use Carbon\Carbon;
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

    public function getArticles($keyword)
    {
        $cacheKey = 'nytimes_articles_' . $keyword;
        $articles = Cache::remember($cacheKey, $this->cacheTime, function () use ($keyword) {
            $response = Http::get('https://api.nytimes.com/svc/search/v2/articlesearch.json', [
                'q' => $keyword,
                'api-key' => $this->apiKey
            ]);
            return $response->json();
        });
        return $this->cleanArticles($articles);
    }

    public function cleanArticles(array $articles)
    {
        $articles_to_db = [];
        $index = 0;
        if ($articles['status'] === 'OK') {
            foreach ($articles['response']['docs'] as $article) {
                $index++;
                $articles_to_db[$index]['title'] = $article['headline']['print_headline'];
                $articles_to_db[$index]['content'] = $article['lead_paragraph'];
                $articles_to_db[$index]['description'] = $article['abstract'];
                $articles_to_db[$index]['url'] = $article['web_url'];
                $articles_to_db[$index]['url_to_image'] = null;
                $articles_to_db[$index]['published_at'] = Carbon::parse($article['pub_date'])->format('Y-m-d H:i:s');
                $articles_to_db[$index]['source'] = $article['source'];
                $articles_to_db[$index]['author'] = $article['byline']['original'];
                $articles_to_db[$index]['category'] = $article['section_name'];
            }
            return $articles_to_db;
        }
    }
}
