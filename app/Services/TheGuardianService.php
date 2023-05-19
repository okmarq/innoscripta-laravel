<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TheGuardianService
{
    protected $apiKey;
    protected $cacheTime = 3600; // Cache articles for 1 hour

    public function __construct()
    {
        $this->apiKey = config('services.guardian.api_key');
    }

    public function getArticles($keyword)
    {
        $cacheKey = 'guardian_articles_' . $keyword;
        $articles = Cache::remember($cacheKey, $this->cacheTime, function () use ($keyword) {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey
            ])->get('https://content.guardianapis.com/search', [
                'query' => [
                    'q' => $keyword
                ]
            ]);
            return $response->json();
        });
        return $this->cleanArticles($articles);
    }

    public function cleanArticles(array $articles)
    {
        $articles_to_db = [];
        $index = 0;
        if ($articles['response']['status'] === 'ok') {
            foreach ($articles['response']['results'] as $article) {
                $index++;
                $articles_to_db[$index]['title'] = $article['webTitle'];
                $articles_to_db[$index]['content'] = null;
                $articles_to_db[$index]['description'] = null;
                $articles_to_db[$index]['url'] = $article['webUrl'];
                $articles_to_db[$index]['url_to_image'] = null;
                $articles_to_db[$index]['published_at'] = Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s');
                $articles_to_db[$index]['source'] = $article['sectionName'];
                $articles_to_db[$index]['author'] = $article['pillarName'];
                $articles_to_db[$index]['category'] = $article['type'];
            }
            return $articles_to_db;
        }
    }
}