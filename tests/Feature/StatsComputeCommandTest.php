<?php

use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\artisan;

beforeEach(function () {
    Cache::flush();
});

it('computes a snapshot from cache stats', function () {
    // Seed some fake stats
    Cache::forever('stats:queries', [
        'people->obi' => 3,
        'people->an'  => 1,
    ]);

    Cache::forever('stats:movies_in_results', [
        'A New Hope' => 5,
        'Jedi'       => 2,
    ]);

    Cache::forever('stats:movies_hits', [
        'A New Hope' => 10,
    ]);

    Cache::forever('stats:characters_hits', [
        'Obi-Wan Kenobi' => 4,
    ]);

    Cache::forever('stats:searches_by_bucket', [
        '2025-12-10 07:05' => 2,
        '2025-12-10 07:10' => 5,
    ]);

    // SWAPI low-level stats
    Cache::forever('stats:swapi:req_length_sum', 300);
    Cache::forever('stats:swapi:req_count', 10);
    Cache::forever('stats:swapi:cache_hits', 8);
    Cache::forever('stats:swapi:total_requests', 10);
    Cache::forever('stats:swapi:time_sum', 1000);
    Cache::forever('stats:swapi:time_count', 4);
    Cache::forever('stats:swapi:time_min', 50);
    Cache::forever('stats:swapi:time_max', 400);

    artisan('stats:compute')->assertExitCode(0);

    $snapshot = Cache::get('stats:snapshot:latest');

    expect($snapshot)->not->toBeNull()
        ->and($snapshot)->toHaveKeys([
            'generated_at',
            'top_queries',
            'movies_in_results',
            'most_accessed_movies',
            'most_accessed_characters',
            'time_buckets',
            'average_request_length',
            'cache_hit_percentage',
            'request_timing',
        ]);

    // average_request_length = 300 / 10
    expect($snapshot['average_request_length'])->toBeFloat()
        ->and($snapshot['average_request_length'])->toEqual(30.0);

    // cache_hit_percentage = 8 / 10 * 100
    expect($snapshot['cache_hit_percentage'])->toEqual(80.0);

    // timing stats
    expect($snapshot['request_timing'])
        ->toMatchArray([
            'avg_ms' => 250.0, // 1000 / 4
            'min_ms' => 50,
            'max_ms' => 400,
            'count'  => 4,
        ]);

    // top_queries should be sorted (3 then 1)
    expect($snapshot['top_queries']['people->obi'])->toBe(3);
    expect($snapshot['top_queries']['people->an'])->toBe(1);
});
