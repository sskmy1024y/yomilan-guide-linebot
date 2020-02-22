<?php

namespace Tests\Feature;

use App\Services\Route\Route_Generate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteGenerateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function routeGenerate()
    {
        $array = [];
        $auto = new Route_Generate($array);
        $auto->make();
    }
}
