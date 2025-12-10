<?php

namespace App\Http\Controllers;

use App\Services\StatsRecorder;
use Inertia\Inertia;
use SWApi\Resources\Movie;
use SWApi\Resources\Person;

class SearchController
{
    public function index()
    {
        return Inertia::render('search');
    }

    public function people(Person $service, StatsRecorder $stats) {
        $result = $service->search($q = request()->input('q'))->map(fn($person) => [
            'id' => $person['id'],
            'title' => $person['name'],
        ]);

        $stats->recordCharacterSearch(query: 'people->'.$q, characterNames: $result->pluck('title')->all());

        return response()->json($result);
    }

    public function movies(Movie $movies, StatsRecorder $stats)
    {
        $result = $movies->search($q = request()->input('q'))->values();

        $stats->recordMovieSearch(query: 'movie->'.$q, movieTitles: $result->pluck('title')->all());

        return response()->json($result);
    }
}
