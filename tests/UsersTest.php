<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;

final class UsersTest extends AbstractTest
{

    public function testGetCollection(): void
    {
        $response = $this->createClientWithCredentials()->request('GET', '/api/users');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        $this->assertCount(10, $response->toArray());

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(User::class, null, 'json');
    }

    public function testGetItem(): void
    {
        $this->createClientWithCredentials()->request('GET', '/api/users/15');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            'id' => 15,
            'name' => 'User 15',
            'lastname' => 'Lastname 15',
            'adress' => 'Adress 15',
            'creditCard' => '5412597435621548',
            'registeredAt' => '2022-01-01T10:00:00+00:00'
        ]);

        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testCreateUser(): void
    {
        $response = $this->createClientWithCredentials()->request('POST', '/api/users', ['json' => [
            'name' => 'new user',
            'lastname' => 'new user',
            'adress' => 'new user adress',
            'creditCard' => '5789654812455414',
            'registeredAt' => '2022-01-01T10:00:00+00:00',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains([
            'name' => 'new user',
            'lastname' => 'new user',
            'adress' => 'new user adress',
            'creditCard' => '5789654812455414',
            'registeredAt' => '2022-01-01T10:00:00+00:00',
        ]);
        $this->assertMatchesRegularExpression('~^\d+$~', $response->toArray()['id']);
        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testCreateUserWithInvalidName(): void
    {
        $this->createClientWithCredentials()->request('POST', '/api/users', ['json' => [
            'name' => 'ap',
            'lastname' => 'UserWithInvalidName',
            'adress' => 'UserWithInvalidName',
            'creditCard' => '5789654812455414',
            'registeredAt' => '2022-01-01T10:00:00+00:00',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => "name: Votre nom d'utilisateur doit contenir au minimum 3 lettres."
        ]);
    }

    public function testCreateUserWithInvalidLastname(): void
    {
        $this->createClientWithCredentials()->request('POST', '/api/users', ['json' => [
            'name' => 'UserWithInvalidLastname',
            'lastname' => '',
            'adress' => 'UserWithInvalidLastname',
            'creditCard' => '5789654812455414',
            'registeredAt' => '2022-01-01T10:00:00+00:00',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => "lastname: This value should not be blank."
        ]);
    }

    public function testCreateUserWithInvalidAdress(): void
    {
        $this->createClientWithCredentials()->request('POST', '/api/users', ['json' => [
            'name' => 'UserWithInvalidAdress',
            'lastname' => 'UserWithInvalidAdress',
            'adress' => '',
            'creditCard' => '5789654812455414',
            'registeredAt' => '2022-01-01T10:00:00+00:00',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => "adress: This value should not be blank."
        ]);
    }

    public function testCreateUserWithInvalidCreditCard(): void
    {
        $this->createClientWithCredentials()->request('POST', '/api/users', ['json' => [
            'name' => 'UserWithInvalidCreditCard',
            'lastname' => 'UserWithInvalidCreditCard',
            'adress' => 'UserWithInvalidCreditCard',
            'creditCard' => '5789654812455414541545484',
            'registeredAt' => '2022-01-01T10:00:00+00:00',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => "creditCard: This value should have exactly 16 characters."
        ]);
    }

    public function testCreateUserWithInvalidTimeStamp(): void
    {
        $this->createClientWithCredentials()->request('POST', '/api/users', ['json' => [
            'name' => 'UserWithInvalidTimeStamp',
            'lastname' => 'UserWithInvalidTimeStamp',
            'adress' => 'UserWithInvalidTimeStamp',
            'creditCard' => '5789654812455414',
            'registeredAt' => '2022-01-:00:00+00:00',
        ]]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => "Failed to parse time string (2022-01-:00:00+00:00) at position 7 (-): Unexpected character"
        ]);
    }

    public function testCreateUserWithNoTimeStamp(): void
    {
        $this->createClientWithCredentials()->request('POST', '/api/users', ['json' => [
            'name' => 'UserWithInvalidTimeStamp',
            'lastname' => 'UserWithInvalidTimeStamp',
            'adress' => 'UserWithInvalidTimeStamp',
            'creditCard' => '5789654812455414',
            'registeredAt' => '',
        ]]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => "The data is either an empty string or null, you should pass a string that can be parsed with the passed format or a valid DateTime string."
        ]);
    }


    public function testUpdateUser(): void
    {
        $client = $this->createClientWithCredentials();
        $iri = $this->findIriBy(User::class, ['name' => 'User 8']);

        $client->request('PUT', $iri, ['json' => [
            'name' => 'User 8',
            'lastname' => 'Lastname 8',
            'adress' => 'Adress 8',
            'creditCard' => '4552369812150756',
            'registeredAt' => '2022-04-22T01:38:21+00:00'
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'User 8',
            'lastname' => 'Lastname 8',
            'adress' => 'Adress 8',
            'creditCard' => '4552369812150756',
            'registeredAt' => '2022-04-22T01:38:21+00:00'
        ]);
    }

    public function testDeleteUser(): void
    {
        $client = $this->createClientWithCredentials();
        $iri = $this->findIriBy(User::class, ['id' => 15]);

        $client->request('DELETE', $iri);
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            $userRepository->findOneBy(['id' => 15])
        );
    }
}
