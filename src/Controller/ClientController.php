<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/clients")
 */
class ClientController
{
    /**
     * @Route("/{id}/users", name="api_clients_users_get", methods={"GET"})
     */
    public function users(Client $client, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($userRepository->findBy(['client' => $client]), 'json', ['groups' => 'getAll']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
