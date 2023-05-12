<?php

namespace App\Services;

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

    public function getArticles($keyword, $date, $category, $source)
    {
        $cacheKey = 'guardian_articles_' . $keyword . '_' . $date . '_' . $category . '_' . $source;
        return Cache::remember($cacheKey, $this->cacheTime, function () use ($keyword, $date, $category, $source) {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey
            ])->get('https://content.guardianapis.com/search', [
                'query' => [
                    'q' => $keyword,
                    'from-date' => $date,
                    'section' => $category,
                    'source' => $source
                ]
            ]);
            return $response->json();
        });
    }
}
