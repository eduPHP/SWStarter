<?php

use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\getJson;

beforeEach(function () {
    Cache::flush();
});

it('returns 503 when stats snapshot is missing', function () {
    $response = getJson('/api/stats');

    $response
        ->assertStatus(503)
        ->assertJsonStructure(['message']);
});

it('returns latest stats snapshot', function () {
    Cache::forever('stats:snapshot:latest', [
        'generated_at' => '2025-12-10T07:13:00+00:00',
        'top_queries' => ['people->obi' => 3],
        'movies_in_results' => [],
        'most_accessed_movies' => [],
        'most_accessed_characters' => [],
        'time_buckets' => [],
        'average_request_length' => 30.0,
        'cache_hit_percentage' => 80.0,
        'request_timing' => [
            'avg_ms' => 250.0,
            'min_ms' => 50,
            'max_ms' => 400,
            'count'  => 4,
        ],
    ]);

    $response = getJson('/api/stats');

    $response
        ->assertStatus(200)
        ->assertJson([
            'generated_at' => '2025-12-10T07:13:00+00:00',
            'average_request_length' => 30.0,
            'cache_hit_percentage' => 80.0,
            'request_timing' => [
                'avg_ms' => 250.0,
                'min_ms' => 50,
                'max_ms' => 400,
                'count'  => 4,
            ],
        ])
        ->assertJsonStructure([
            'generated_at',
            'top_queries',
            'movies_in_results',
            'most_accessed_movies',
            'most_accessed_characters',
            'time_buckets',
            'average_request_length',
            'cache_hit_percentage',
            'request_timing' => ['avg_ms', 'min_ms', 'max_ms', 'count'],
        ]);
});
