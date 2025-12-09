<?php

uses(\SWApi\tests\TestCase::class);

it('can search for a person', function () {
    $this->fakeApiCall('people', 'people-search');

    $service = new \SWApi\Resources\Person();

    $response = $service->search('bi');

    expect($response)->toHaveCount(4);
    expect($response[0]['name'])->toBe('Biggs Darklighter')->and($response[0]['id'])->toBe(9);
    expect($response[3]['name'])->toBe('Bib Fortuna')->and($response[3]['id'])->toBe(45);

    Http::assertSent(function ($request) {
        return $request->data()['name'] === 'bi';
    });
});

it('can find a person by id', function () {
    $this->fakeApiCall([
        'people/9' => 'person-9',
        'films/1' => 'film-1',
    ]);

    $service = new \SWApi\Resources\Person();

    $person = $service->findById(9);

    expect($person['name'])->toBe('Biggs Darklighter')->and($person['id'])->toBe(9);
    expect($person['details'])->toHaveCount(6)->and($person['movies'])->toHaveCount(1);
    expect($person['details'][0]['label'])->toBe('Gender');
});
