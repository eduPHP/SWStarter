<?php

use Inertia\Testing\AssertableInertia as Assert;

uses(\SWApi\Tests\TestCase::class);

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
    ]);
    $service = new \SWApi\Resources\Movie();

    $movie = $service->findById(1);

    expect($movie['title'])->toBe('A New Hope')->and($movie['id'])->toBe(1);
    expect($movie['character_ids'])->toHaveCount(18);
    expect($movie['character_ids'][0])->toBe('1');
    expect($movie['character_ids'][17])->toBe('81');
});

it('loads lazy characters prop', function () {
    $this->fakeApiCall([
        'films/1' => 'film-1',
        'people/81' => 'person-81',
        'people/*' => 'person-9',
    ]);

    $response = $this->get('movies/1');

    $response->assertInertia(fn (Assert $page) =>
    $page
        ->component('movie')
        ->has('movie')
        ->missing('characters') // lazy/deferred prop not in initial response
        ->loadDeferredProps(fn (Assert $reload) =>
        $reload
            ->has('characters')
            ->where('characters',
                fn ($characters) => count($characters) === 18 &&
                    $characters[0]['name'] === 'Biggs Darklighter' &&
                    $characters[17]['name'] === 'Raymus Antilles'
            )
        )
    );
});
