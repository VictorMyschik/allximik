<?php

namespace Tests\Feature\API\Auth;

use Tests\BaseTest;

class LogoutTokenTest extends BaseTest
{
  public function testLogout(): void
  {
    // try just logout
    $response = $this->post(route('api.logout'), []);
    self::assertEquals(400, $response->getStatusCode());
    $body = json_decode($response->getContent(), true);
    self::assertEquals('Unauthorized', $body['error']);

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

    self::assertTrue($body['result']);
    self::assertNotEmpty($body['content']);
    $content = $body['content'];
    $oldToken = $content['access_token'];

    self::assertNotEmpty($content['access_token']);
    self::assertEquals('bearer', $content['token_type']);
    self::assertEquals(3600, $content['expires_in']);

    // Try logout
    $header = ['Authorization' => 'Bearer ' . $oldToken];

    $response = $this->post(route('api.logout'), [], $header);
    self::assertEquals(200, $response->getStatusCode());
    $body = json_decode($response->getContent(), true);
    self::assertEquals('User successfully signed out', $body['message']);

    $user->delete();
  }
}
