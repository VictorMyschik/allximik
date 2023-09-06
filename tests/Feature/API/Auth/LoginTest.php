<?php

namespace Tests\Feature\API\Auth;

use Tests\BaseTest;

class LoginTest extends BaseTest
{
  public function testSuccessLogin(): void
  {
    $email = self::randomEmail();
    $password = self::randomString(20);

    $user = self::createUser($email, $password);

    $args = [
      'email'    => $email,
      'password' => $password,
    ];

    $response = $this->post(route('api.login'), $args);
    self::assertEquals(200, $response->getStatusCode());
    self::assertNotEmpty($response->getContent());

    $body = json_decode($response->getContent(), true);

    self::assertNotEmpty($body['access_token']);
    self::assertEquals('bearer', $body['token_type']);
    self::assertEquals(3600, $body['expires_in']);

    $user->delete();
  }

  public function testMissingLogin(): void
  {
    $email = self::randomEmail();
    $password = self::randomString(20);

    $user = self::createUser($email, $password);

    $args = [
      'email'    => $email,
      'password' => 'fake_password',
    ];

    $response = $this->post(route('api.login'), $args);
    self::assertEquals(401, $response->getStatusCode());
    self::assertNotEmpty($response->getContent());

    $body = json_decode($response->getContent(), true);

    self::assertNotEmpty($body['error']);
    self::assertEquals('Unauthorized', $body['error']);

    $user->delete();
  }
}
