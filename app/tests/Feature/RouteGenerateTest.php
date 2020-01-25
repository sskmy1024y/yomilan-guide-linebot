<?php

namespace Tests\Feature;

use App\Services\Course\CourseGenerate;
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
        $auto = new CourseGenerate($array);
        $auto->generate();
    }
}
