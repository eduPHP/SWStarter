<?php

namespace SWApi;

use App\Services\StatsRecorder;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SWApiClient
{
    protected PendingRequest $client;
    protected StatsRecorder $stats;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('swapi.base_url'));
        $this->stats = new StatsRecorder;
    }

    public function get($path, array $query = [])
    {
        $key = sha1(json_encode(func_get_args()));
        $hitFromCache = true;

        $start = microtime(true);

        $result = cache()->remember(
            $key,
            now()->addDay(),
            function () use ($path, $query, &$hitFromCache) {
                $hitFromCache = false;

                $response = $this->client->get($path, $query);

                return $response->json('result', []);
            }
        );

        $durationMs = (int) round((microtime(true) - $start) * 1000);

        $this->stats->recordCacheStats($path, $query, $hitFromCache, $durationMs);

        return $result;
    }
}
