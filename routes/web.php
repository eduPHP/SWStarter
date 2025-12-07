<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('search');
})->name('search');

Route::get('/people/{person}', function () {
    return Inertia::render('person', [
        'person' => [
            'name' => 'Test',
            'details' => [
                ['name' => 'Test Detail', 'value' => 'Test Value'],
            ],
            'movies' => [
                ['title' => 'Test Movie', 'id' => 1],
                ['title' => 'Another Movie', 'id' => 2],
            ]
        ]
    ]);
});

Route::get('/movies/{movie}', function () {
    return Inertia::render('movie', [
        'movie' => [
            'title' => 'Test movie',
            'details' => 'Luke Skywalker has returned to
his home planet of Tatooine in
an attempt to rescue his
friend Han Solo from the
clutches of the vile gangster
Jabba the Hutt.

Little does Luke know that the
GALACTIC EMPIRE has secretly
begun construction on a new
armored space station even
more powerful than the first
dreaded Death Star.

When completed, this ultimate
weapon will spell certain doom
for the small band of rebels
struggling to restore freedom
to the galaxy...',
            'characters' => [
                ['name' => 'someone', 'id' => 1],
                ['name' => 'foo', 'id' => 4],
                ['name' => 'bar', 'id' => 7],
                ['name' => 'asdasdasda', 'id' => 8],
                ['name' => 'sdfsdf sdfsf', 'id' => 9],
                ['name' => 'bar', 'id' => 10],
                ['name' => 'asdadasdasda', 'id' => 11],
                ['name' => 'bar', 'id' => 12],
                ['name' => 'hgfhfghdfgh', 'id' => 15],
            ]
        ]
    ]);
});
