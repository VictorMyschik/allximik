<?php

namespace API\Auth;

use App\Models\User;
use Tests\TestBase;

class RegisterTest extends TestBase
{
  public function testSuccessRegister(): void
  {
    $email = self::randomEmail();
    $password = self::randomString(20);

    $args = [
      'name'                  => 'Test User',
      'email'                 => $email,
      'password'              => $password,
      'password_confirmation' => $password,
    ];

    $response = $this->post(route('api.register'), $args);
    self::assertEquals(200, $response->getStatusCode());
    self::assertNotEmpty($response->getContent());

    $body = json_decode($response->getContent(), true);

    self::assertTrue($body['result']);
    self::assertNotEmpty($body['content']);
    self::assertNotEmpty($body['content']['access_token']);
    self::assertEquals('bearer', $body['content']['token_type']);
    self::assertEquals(3600, $body['content']['expires_in']);

    $user = User::where('email', $email)->first();
    self::assertNotEmpty($user);
    self::assertEquals($email, $user->email);

    $user->delete();
  }
}
