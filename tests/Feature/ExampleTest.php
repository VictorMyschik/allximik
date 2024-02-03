<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\System\Translate;
use Tests\TestCase;

class ExampleTest extends TestCase
{
  /**
   * A basic test example.
   */
  public function test_the_application_returns_a_successful_response(): void
  {
    $r = Translate::getFullList('ru');
  }
}
