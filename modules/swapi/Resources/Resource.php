<?php

namespace SWApi\Resources;

use Illuminate\Support\Collection;
use SWApi\SWApiService;

abstract class Resource
{
    protected SWApiService $service;
    protected string $searchable = '';
    protected string $path = '';

    public function __construct()
    {
        if (!$this->searchable || !$this->path) {
            throw new \Exception('Searchable and path must be set on resource');
        }
        $this->service = app()->make('SWApi');
    }

    public function search(string $query): Collection
    {
        $results = $this->service->get($this->path, [$this->searchable => $query]);

        return collect($results)->map(fn($movie) => static::mapBasic($movie));
    }

    public static function findByUrl($url): array
    {
        $id = substr($url, strrpos($url, '/') + 1);

        return (new static)->findById($id, expanded: false);
    }

    public function findById(int $id, bool $expanded = true): array
    {
        $response = $this->service->get($this->path."/$id");

        return $expanded ? static::mapFull($response) : static::mapBasic($response);
    }

    abstract public static function mapBasic(array $resource): array;
    abstract public static function mapFull(array $resource): array;

}
