<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends AbstractTestController
{
    public function testGetProductList()
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/products');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testGetProductListWithInvalidToken()
    {
        $client = static::createClient();
        $client->request('GET', '/api/products');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetProductDetails()
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/products/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testGetProductDetailsNotFound()
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/products/9999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
