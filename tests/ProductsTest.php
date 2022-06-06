<?php

namespace App\Tests;

use App\Entity\Product;

final class ProductsTest extends AbstractTest
{
    public function testGetCollection(): void
    {
        $response = $this->createClientWithCredentials()->request('GET', '/api/products');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        $this->assertCount(10, $response->toArray());

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Product::class, null, 'json');
    }

    public function testGetItem(): void
    {
        $this->createClientWithCredentials()->request('GET', '/api/products/15');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            'name' => 'Smartphone 15',
            'description' => 'Description produit 15',
            'image' => 'phone.jpg',
            'slug' => 'smartphone-15',
            'priceWT' => 439.6,
            'priceATI' => 550,
            'addedAt' => '2022-01-01T10:00:00+00:00',
        ]);

        $this->assertMatchesResourceItemJsonSchema(Product::class);
    }
}
