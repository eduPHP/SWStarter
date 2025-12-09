<?php

namespace SWApi\Resources;

class Person extends Resource
{
    protected string $searchable = 'name';
    protected string $path = '/people';

    public static function mapBasic(array $resource): array
    {
        return [
            'name' => $resource['properties']['name'],
            'id' => (int)$resource['uid'],
        ];
    }

    public static function mapFull(array $resource): array
    {
        return [
            ...self::mapBasic($resource),
            'details' => [
                ['label' => 'Gender', 'value' => $resource['properties']['gender']],
                ['label' => 'Birth Year', 'value' => $resource['properties']['birth_year']],
                ['label' => 'Eye Color', 'value' => $resource['properties']['eye_color']],
                ['label' => 'Hair Color', 'value' => $resource['properties']['hair_color']],
                ['label' => 'Height', 'value' => $resource['properties']['height']],
                ['label' => 'Mass', 'value' => $resource['properties']['mass']],
            ],
            'movies' => collect($resource['properties']['films'])->map(
                fn($movieUrl) => Movie::findByUrl($movieUrl)
            )->toArray(),
        ];
    }
}
