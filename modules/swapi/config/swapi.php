<?php

return [
    'base_url' => env('SWAPI_BASE_URL', 'https://swapi.tech/api/'),
    'resources' => [
        'people' => \SWApi\Resources\Person::class,
        'movies' => \SWApi\Resources\Movie::class,
    ]
];
