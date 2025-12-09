<?php

namespace SWApi;

class SWApiServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/swapi.php', 'swapi');

        $this->app->bind('SWApi', function () {
            return new SWApiClient;
        });
    }
}
