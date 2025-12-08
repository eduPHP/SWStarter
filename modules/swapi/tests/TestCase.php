<?php

namespace SWApi\tests;

use Illuminate\Support\Facades\Http;

class TestCase extends \Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();


    }

    public function fakeApiCall(string $path, string $fixture): void
    {
        $base = rtrim(config('swapi.base_url'), '/');
        $path = trim($path, '/');

        Http::preventStrayRequests();

        $fixtureFile = __DIR__ . '/fixtures/' . $fixture . '.json';
        if (file_exists($fixtureFile)) {
            Http::fake([
                "$base/$path*"  => Http::response(file_get_contents($fixtureFile), 200),
            ]);
        }
    }
}
