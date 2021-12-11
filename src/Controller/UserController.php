<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/users")
 */
class UserController
{
    /**
     * @Route("/", name="api_users_get_all", methods={"GET"})
     */
    public function getAll(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($userRepository->findAll(), 'json', ['groups' => 'getAll']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_users_get_item", methods={"GET"})
     */
    public function getItem(User $user, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($user, 'json', ['groups' => 'getItem']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
