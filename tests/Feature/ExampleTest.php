<?php

test('returns a successful response on home', function () {
    $response = $this->withoutVite()->get('/');

    $response->assertStatus(200);
});
