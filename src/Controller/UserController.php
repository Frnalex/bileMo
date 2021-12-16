<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface as SerializerSerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/users")
 */
class UserController
{
    /**
     * @Route("/", name="api_users_get_list", methods={"GET"})
     */
    public function getList(
        UserRepository $userRepository,
        SerializerInterface $serializer,
        Security $security
    ): JsonResponse {
        $data = $serializer->serialize(
            $userRepository->findBy(['client' => $security->getUser()]),
            'json',
            SerializationContext::create()->setGroups(['list'])
        );

        return new JsonResponse(
            $data,
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_users_get_details", methods={"GET"})
     */
    public function getDetails(User $user, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(['details'])),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/", name="api_users_create", methods={"POST"})
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        Security $security,
        ValidatorInterface $validator,
        SerializerSerializerInterface $serializerInterface
    ): JsonResponse {
        /** @var User $user */
        $user = $serializerInterface->deserialize($request->getContent(), User::class, 'json');
        $user->setClient($security->getUser());

        $errors = $validator->validate($user);

        if ($errors->count() > 0) {
            return new JsonResponse(
                $serializer->serialize($errors, 'json'),
                JsonResponse::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(['details'])),
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
