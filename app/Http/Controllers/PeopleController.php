<?php

namespace App\Http\Controllers;

use App\Services\StatsRecorder;
use Inertia\Inertia;
use SWApi\Resources\Movie;
use SWApi\Resources\Person;

class PeopleController
{

    public function show($id, Person $people, Movie $movies, StatsRecorder $stats)
    {
        $person = $people->findById($id);

        $stats->recordCharacterDetails($person['name']);

        return Inertia::render('person', [
            'person' => $person,
            'movies' => Inertia::defer(fn () => $movies->findManyById($person['movie_ids']))
        ]);
    }
}
