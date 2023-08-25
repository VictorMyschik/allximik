<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
  /**
   * A basic test example.
   */
  public function test_the_application_returns_a_successful_response(): void
  {

    User::factory()->create([
      'name'     => 'John Doe',
      'email'    => '',
      'password' => '',
    ]);

    $r = User::count();
    self::assertNotNull($r);

    echo 'count: ' . $r . PHP_EOL;

    $response = $this->get('/');

    $response->assertStatus(200);
  }
}
