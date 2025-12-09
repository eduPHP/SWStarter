<?php

return [
    'base_url' => 'https://swapi.tech/api/',
    'resources' => [
        'people' => \SWApi\Resources\Person::class,
        'movies' => \SWApi\Resources\Movie::class,
    ]
];
