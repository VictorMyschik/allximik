<?php

namespace Tests\Helpers;

trait AuthTrait
{
    public string $authToken;

    protected function login(string $email, string $password): void
    {
        [$code, $content] = self::doPost(route('api.login'), [
            'email'    => $email,
            'password' => $password,
        ]);

        self::assertEquals(200, $code);
        self::assertArrayHasKey('access_token', $content['content']);
        self::assertNotEmpty($content['content']['access_token']);

        $this->authToken = $content['content']['access_token'];
    }
}
