<?php

namespace App\Http\Controllers;

use App\Services\StatsRecorder;
use Inertia\Inertia;
use SWApi\Resources\Movie;
use SWApi\Resources\Person;

class MoviesController
{

    public function show($id, Person $people, Movie $service, StatsRecorder $stats)
    {
        $movie = $service->findById($id);

        $stats->recordMovieDetails($movie['title']);

        return Inertia::render('movie', [
            'movie' => $movie,
            'characters' => Inertia::defer(fn () => $people->findManyById($movie['character_ids']))
        ]);
    }
}
