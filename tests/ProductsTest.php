<?php

namespace App\Tests;

use App\Entity\Product;

final class ProductsTest extends AbstractTest
{
    public function testGetCollection(): void
    {
        $token = $this->getToken([
            'email' => '1@b2b.com',
            'password' => 'password',
        ]);

        $response = $this->createClientWithCredentials($token)->request('GET', '/users', '/products');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/contexts/Product',
            '@id' => '/products',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 20,
            'hydra:view' => [
                '@id' => '/products?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/products?page=1',
                'hydra:last' => '/products?page=2',
                'hydra:next' => '/books?page=2',
            ],
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(30, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Product::class);
    }
}