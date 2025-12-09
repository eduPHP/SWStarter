<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('search');
})->name('search');

Route::get('/find/people', function (\SWApi\Resources\Person $service) {
    return $service->search(request()->input('q'))->map(fn($person) => [
        'id' => $person['id'],
        'title' => $person['name'],
    ]);
});

Route::get('/find/movies', function (\SWApi\Resources\Movie $service) {
    return $service->search(request()->input('q'))->values();
});

Route::get('/people/{id}', function ($id, \SWApi\Resources\Person $service) {
    $person = $service->findById($id);

    return Inertia::render('person', [
        'person' => $person,
    ]);
});

Route::get('/movies/{id}', function ($id, \SWApi\Resources\Movie $service) {
    $movie = $service->findById($id);

    return Inertia::render('movie', [
        'movie' => $movie,
    ]);
});
