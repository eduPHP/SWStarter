<?php

namespace SWApi\Resources;

class Movie extends Resource
{
    protected string $searchable = 'title';
    protected string $path = '/films';

    public static function mapFull($resource): array
    {
        return [
            ...self::mapBasic($resource),
            'details' => $resource['properties']['opening_crawl'],
            'characters' => collect($resource['properties']['characters'])->map(
                fn($character) => Person::findByUrl($character)
            )->toArray(),
        ];
    }

    public static function mapBasic($resource): array
    {
        return [
            'title' => $resource['properties']['title'],
            'id' => (int)$resource['uid'],
        ];
    }
}
