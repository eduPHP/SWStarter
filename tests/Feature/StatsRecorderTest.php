<?php

use App\Services\StatsRecorder;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    // ensure it runs synchronously
    config([
        'cache.default' => 'array',
        'queue.default' => 'sync',
    ]);
    Cache::flush();
});

it('records movie search stats using titles and character names', function () {
    $recorder = app(StatsRecorder::class);

    $recorder->recordMovieSearch(
        query: 'a',
        movieTitles: [
            'A New Hope',
            'Attack of the Clones',
        ],
    );

    $recorder->recordCharacterSearch(
        query: 'bi',
        characterNames: [
            'Obi-Wan Kenobi',
            'Jar Jar Binks',
            'Jar Jar Binks',
        ],
    );

    $queries = Cache::get('stats:queries', []);
    $moviesInResults = Cache::get('stats:movies_in_results', []);
    $charactersInResults = Cache::get('stats:characters_in_results', []);
    $buckets = Cache::get('stats:searches_by_bucket', []);

    expect($queries)
        ->toHaveKey('a', 1);

    expect($moviesInResults)
        ->toHaveKey('A New Hope', 1)
        ->and($moviesInResults)
        ->toHaveKey('Attack of the Clones', 1);

    expect($charactersInResults)
        ->toHaveKey('Obi-Wan Kenobi', 1)
        ->and($charactersInResults)
        ->toHaveKey('Jar Jar Binks', 2);

    expect($buckets)
        ->not->toBeEmpty();
});

it('records movie and character details hits using titles and names', function () {
    $recorder = app(StatsRecorder::class);

    $recorder->recordMovieDetails('A New Hope');
    $recorder->recordMovieDetails('A New Hope');
    $recorder->recordCharacterDetails('Obi-Wan Kenobi');

    $moviesHits = Cache::get('stats:movies_hits', []);
    $charactersHits = Cache::get('stats:characters_hits', []);

    expect($moviesHits)
        ->toHaveKey('A New Hope', 2);

    expect($charactersHits)
        ->toHaveKey('Obi-Wan Kenobi', 1);
});
