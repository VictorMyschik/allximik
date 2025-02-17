<?php

namespace Tests\Feature\API\Auth;

use Tests\TestBase;

class LoginTest extends TestBase
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

        self::assertTrue($body['result']);
        self::assertNotEmpty($body['content']);
        self::assertNotEmpty($body['content']['access_token']);
        self::assertEquals('bearer', $body['content']['token_type']);
        self::assertEquals(3600, $body['content']['expires_in']);

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
        self::assertEquals(400, $response->getStatusCode());
        self::assertNotEmpty($response->getContent());

        $body = json_decode($response->getContent(), true);

        self::assertFalse($body['result']);
        self::assertNotEmpty($body['error']);
        self::assertEquals('Invalid credentials', $body['error']);

        $user->delete();
    }
}
