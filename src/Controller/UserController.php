<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface as SerializerSerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/users")
 *
 * @OA\Tag(name="Users")
 */
class UserController
{
    /**
     * Liste des utilisateurs.
     *
     * @Route("/", name="api_users_get_list", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Liste des utilisateurs",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"list"}))
     *     )
     * )
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
     * Détails d'un utilisateur.
     *
     * @Route("/{id}", name="api_users_get_details", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Details d'un utilisateur",
     *     @Model(type=User::class, groups={"details"})
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="L'utilisateur n'existe pas",
     * )
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="L'id de l'utilisateur",
     *     @OA\Schema(type="integer")
     * )
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
     * Créer un nouvel utilisateur.
     *
     * @Route("/", name="api_users_create", methods={"POST"})
     *
     * @OA\Response(
     *     response=201,
     *     description="L'utilisateur qui vient d'être créé",
     *     @Model(type=User::class, groups={"details"})
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="Les informations envoyées ne sont pas correctes",
     * )
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(property="email", type="string", format="email", example="john.doe@yopmail.fr"),
     *         @OA\Property(property="firstname", type="string", example="John"),
     *         @OA\Property(property="lastname", type="string", example="Doe"),
     *       )
     *     )
     *   )
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
     * Supprimer un utilisateur.
     *
     * @Route("/{id}", name="api_users_delete", methods={"DELETE"})
     *
     * @OA\Response(
     *     response=204,
     *     description="no content",
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="L'utilisateur n'existe pas",
     * )
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="L'id de l'utilisateur",
     *     @OA\Schema(type="integer")
     * )
     */
    public function delete(User $user, EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
