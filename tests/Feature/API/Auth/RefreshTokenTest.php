<?php

namespace Tests\Feature\API\Auth;

use Exception;
use GuzzleHttp\Client;
use Tests\TestBase;
use Throwable;

class RefreshTokenTest extends TestBase
{
  public function testRefreshToken(): void
  {
    $client = new Client();

    $email = self::randomEmail();
    $password = self::randomString(20);

    $user = self::createUser($email, $password);

    $args = [
      'email'    => $email,
      'password' => $password,
    ];

    $response = $client->post(route('api.login'), ['form_params' => $args]);
    self::assertEquals(200, $response->getStatusCode());

    $body = json_decode($response->getBody()->getContents(), true);
    $content = $body['content'];

    self::assertNotEmpty($content['access_token']);
    self::assertEquals('bearer', $content['token_type']);
    self::assertEquals(3600, $content['expires_in']);

    // Refresh
    $oldToken = $content['access_token'];

    $header = ['Authorization' => 'Bearer ' . $oldToken];
    $response = $client->post(route('api.refresh'), ['headers' => $header]);
    self::assertEquals(200, $response->getStatusCode());

    $body = json_decode($response->getBody()->getContents(), true);

    $content = $body['content'];

    self::assertNotEmpty($content['access_token']);
    self::assertEquals('bearer', $content['token_type']);
    self::assertEquals(3600, $content['expires_in']);

    self::assertNotEquals($oldToken, $content['access_token']);

    // Try refresh again with old token
    try {
      $client->post(route('api.refresh'), ['headers' => $header]);
      self::fail('Expected exception not thrown');
    } catch (Throwable $e) {
      self::assertInstanceOf(Exception::class, $e);
      self::assertEquals(400, $e->getCode(), $e->getMessage());
    }

    $user->delete();
  }
}
