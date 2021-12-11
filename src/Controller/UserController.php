<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/", name="api_users_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, ClientRepository $clientRepository, EntityManagerInterface $em): JsonResponse
    {
        /** @var User $user */
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setClient($clientRepository->findOneBy([]));
        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            $serializer->serialize($user, 'json', ['groups' => 'getItem']),
            JsonResponse::HTTP_CREATED,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_users_delete", methods={"DELETE"})
     */
    public function delete(User $user, EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
