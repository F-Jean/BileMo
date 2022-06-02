<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;

abstract class AbstractTest extends ApiTestCase
{
    private ?string $token = null;

    protected function createClientWithCredentials($token = null): Client
    {
        $token = $token ?: $this->getToken();

        return static::createClient([], ['headers' => ['authorization' => 'Bearer ' . $token, 'accept' => 'application/json']]);
    }

    protected function getToken($body = []): string
    {
        if ($this->token) {

            return $this->token;
        }

        $response = static::createClient()->request('POST', '/api/authentication_token', [
            'json' => $body ?: [
                'email' => '1@b2b.com',
                'password' => 'password',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent());
        $this->token = $data->token;

        return $data->token;
    }
}
