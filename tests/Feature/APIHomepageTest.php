<?php

namespace Tests\Feature;

use Tests\TestCase;

class APIHomepageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomepageResponse()
    {
        $response = $this->json('GET', '/' . config('app.api_version'));
        $response->assertStatus(200);
    }
}
