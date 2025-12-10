<?php

namespace SWApi\Tests;

use Illuminate\Support\Facades\Http;

class TestCase extends \Tests\TestCase
{
    public function fakeApiCall(array|string $path, ?string $fixture = null): void
    {
        if (!is_array($path)) {
            $fakeInstructions = [$path => $fixture];
        } else {
            $fakeInstructions = $path;
        }

        $base = rtrim(config('swapi.base_url'), '/');

        $fakeUrls = [];

        foreach ($fakeInstructions as $key => $value) {
            $key = trim($key, '/');

            Http::preventStrayRequests();

            $fixtureFile = __DIR__ . '/fixtures/' . $value . '.json';
            if (file_exists($fixtureFile)) {
                $fakeUrls["$base/$key*"] = Http::response(file_get_contents($fixtureFile), 200);
            }
        }

        Http::fake($fakeUrls);
    }
}
