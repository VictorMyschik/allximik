<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
  /**
   * A basic test example.
   */
  public function test_that_true_is_true(): void
  {
    $r = strlen(crc32(2));
    $this->assertTrue(true);
  }
}
