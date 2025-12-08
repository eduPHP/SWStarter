<?php

uses(\SWApi\tests\TestCase::class);

it('can search for a person', function () {
    $this->fakeApiCall('people', 'people-search');

    $service = new \SWApi\Resources\Person();

    $response = $service->search('bi');

    expect($response)->toHaveCount(4);
    expect($response[0]['name'])->toBe('Biggs Darklighter')->and($response[0]['id'])->toBe(9);
    expect($response[3]['name'])->toBe('Bib Fortuna')->and($response[3]['id'])->toBe(45);
});

it('can find a person by id', function () {
    // $this->fakeApiCall('people/1', 'person-1');

    $service = new \SWApi\Resources\Person();

    $person = $service->findById(9);

    dd($person);
});
