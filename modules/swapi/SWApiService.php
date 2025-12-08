<?php

namespace SWApi;

use Illuminate\Support\Facades\Http;

class SWApiService
{

    protected \Illuminate\Http\Client\PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('swapi.base_url'));
    }

    public function get($path, array $query = [])
    {
        $key = sha1(json_encode(func_get_args()));

        // a simple cache to avoid hitting the rate limit by searching relation data
        return cache()->remember($key, now()->addDay(), function () use ($path, $query) {
            $response = $this->client->get($path, $query);

            return $response->json('result', []);
        });
    }
}
