<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserControllerTest extends AbstractTestController
{
    public function testGetUserList()
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/users');

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_OK);
    }

    public function testGetUserListWithInvalidToken()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users');

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function testGetUserDetails()
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/users/42');

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_OK);
    }

    public function testGetUserDetailsNotFound()
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/users/9999');

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_NOT_FOUND);
    }

    public function testCreateUser()
    {
        $newUser = json_encode([
            'email' => 'test@email.com',
            'firstName' => 'firstName',
            'lastName' => 'lastName',
        ]);

        $client = $this->createAuthenticatedClient();
        $client->request('POST', 'https://localhost:8000/api/users/', [], [], [], $newUser);

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_CREATED);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->newUserId = $data['id'];
    }

    public function testCreateUserWithWrongContent()
    {
        $newUser = json_encode([
            'email' => 'testNoEmail',
            'lastName' => 'lastName',
        ]);

        $client = $this->createAuthenticatedClient();
        $client->request('POST', 'https://localhost:8000/api/users/', [], [], [], $newUser);
        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_BAD_REQUEST);
    }

    public function testDeleteUser()
    {
        $client = $this->createAuthenticatedClient();
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)->findOneBy([], null);

        $client->request('DELETE', 'https://localhost:8000/api/users/'.$user->getId());
        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_NO_CONTENT);
    }

    public function testDeleteUserNotFound()
    {
        $client = $this->createAuthenticatedClient();
        $client->request('DELETE', 'https://localhost:8000/api/users/2');
        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_NOT_FOUND);
    }
}
