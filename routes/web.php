<?php

use App\Services\StatsRecorder;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use SWApi\Resources\Movie;
use SWApi\Resources\Person;

Route::get('/', function () {
    return Inertia::render('search');
})->name('search');

Route::get('/api/stats', function () {
    $snapshot = cache()->get('stats:snapshot:latest');

    if (!$snapshot) {
        // If the scheduled job hasn't generated anything yet
        return response()->json([
            'message' => 'Statistics are not available yet. Try again in a few minutes.',
        ], 503);
    }

    // Optionally enforce a consistent envelope
    return response()->json([
        'generated_at' => $snapshot['generated_at'] ?? null,
        'top_queries' => $snapshot['top_queries'] ?? [],
        'movies_in_results' => $snapshot['movies_in_results'] ?? [],
        'characters_in_results' => $snapshot['characters_in_results'] ?? [],
        'most_accessed_movies' => $snapshot['most_accessed_movies'] ?? [],
        'most_accessed_characters' => $snapshot['most_accessed_characters'] ?? [],
        'time_buckets' => $snapshot['time_buckets'] ?? [],
        'average_request_length' => $snapshot['average_request_length'] ?? null,
        'cache_hit_percentage' => $snapshot['cache_hit_percentage'] ?? null,
        'request_timing' => $snapshot['request_timing'] ?? null,
    ]);
});

Route::get('/find/people', function (Person $service, StatsRecorder $stats) {
    $result = $service->search($q = request()->input('q'))->map(fn($person) => [
        'id' => $person['id'],
        'title' => $person['name'],
    ]);

    $stats->recordCharacterSearch(query: 'people->'.$q, characterNames: $result->pluck('title')->all());

    return $result;
});

Route::get('/find/movies', function (Movie $movies, StatsRecorder $stats) {
    $result = $movies->search($q = request()->input('q'))->values();

    $stats->recordMovieSearch(query: 'movie->'.$q, movieTitles: $result->pluck('title')->all());

    return $result;
});

Route::get('/people/{id}', function ($id, Person $people, Movie $movies, StatsRecorder $stats) {
    $person = $people->findById($id);

    $stats->recordCharacterDetails($person['name']);

    return Inertia::render('person', [
        'person' => $person,
        'movies' => Inertia::defer(fn () => $movies->findManyById($person['movie_ids']))
    ]);
});

Route::get('/movies/{id}', function ($id, Person $people, Movie $service, StatsRecorder $stats) {
    $movie = $service->findById($id);

    $stats->recordMovieDetails($movie['title']);

    return Inertia::render('movie', [
        'movie' => $movie,
        'characters' => Inertia::defer(fn () => $people->findManyById($movie['character_ids']))
    ]);
});
