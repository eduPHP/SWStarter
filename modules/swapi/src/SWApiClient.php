<?php

namespace SWApi;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SWApiClient
{
    protected PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('swapi.base_url'));
    }

    public function get($path, array $query = [])
    {
        $key = sha1(json_encode(func_get_args()));

        $hitFromCache = true;

        $result = cache()->remember(
            $key,
            now()->addDay(),
            function () use ($path, $query, &$hitFromCache) {
                $hitFromCache = false;

                $response = $this->client->get($path, $query);

                return $response->json('result', []);
            }
        );

        $this->recordCacheStats($path, $query, $hitFromCache);

        return $result;
    }

    private function recordCacheStats($path, array $query, bool $hitFromCache): void
    {
        $length = strlen($path . json_encode($query));

        cache()->increment('stats:swapi:total_requests');

        cache()->increment($hitFromCache ? 'stats:swapi:cache_hits' : 'stats:swapi:cache_misses');

        cache()->increment('stats:swapi:req_length_sum', $length);
        cache()->increment('stats:swapi:req_count');
    }
}
