<?php

uses(\SWApi\tests\TestCase::class);

it('can search for movies', function () {
    $this->fakeApiCall('films', 'movie-search');

    $service = new \SWApi\Resources\Movie();

    $response = $service->search('a');

    expect($response)->toHaveCount(4);
    expect($response[0]['title'])->toBe('A New Hope')->and($response[0]['id'])->toBe(1);

    Http::assertSent(function ($request) {
        return $request->data()['title'] === 'a';
    });
});

it('can get a movie details', function () {
    $this->fakeApiCall([
        'films/1' => 'film-1',
        'people/81' => 'person-81',
        'people/*' => 'person-9',
    ]);
    $service = new \SWApi\Resources\Movie();

    $movie = $service->findById(1);

    expect($movie['title'])->toBe('A New Hope')->and($movie['id'])->toBe(1);
    // dd($movie['characters']);
    expect($movie['characters'])->toHaveCount(18);
    expect($movie['characters'][0]['name'])->toBe('Biggs Darklighter');
    expect($movie['characters'][17]['name'])->toBe('Raymus Antilles');
});
