<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products")
 *
 * @OA\Tag(name="Products")
 */
class ProductController
{
    /**
     * Liste des produits.
     *
     * @Route(name="api_products_get_list", methods={"GET"})
     *
     * @OA\Response(
     *      response=200,
     *      description="Liste des produits",
     *      @OA\JsonContent(
     *          @OA\Property(property="page", description="Page courante", type="integer"),
     *          @OA\Property(property="limit", description="Nombre de produits par page", type="integer"),
     *          @OA\Property(property="pages", description="Nombre total de pages", type="integer"),
     *          @OA\Property(property="_links",
     *              @OA\Property(property="self", @OA\Property(property="href", type="string")),
     *              @OA\Property(property="first", @OA\Property(property="href", type="string")),
     *              @OA\Property(property="last", @OA\Property(property="href", type="string")),
     *              @OA\Property(property="next", @OA\Property(property="href", type="string")),
     *          ),
     *          @OA\Property(property="_embedded",
     *              @OA\Property(property="items",type="array",
     *                  @OA\Items(ref=@Model(type=Product::class, groups={"list"}))
     *              )
     *          )
     *      )
     * )
     *
     * @OA\Response(
     *     response=401,
     *     description="Le token est manquant ou n'est pas valide"
     * )
     *
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Le numéro de la page",
     *     @OA\Schema(type="integer")
     * )
     *
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Le nombre de résultats à afficher",
     *     @OA\Schema(type="integer")
     * )
     */
    public function getList(Request $request, ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 5);
        $offset = $page * $limit - $limit;
        $totalPages = ceil($productRepository->count([]) / $limit);

        $products = $productRepository->findBy([], [], $limit, $offset);

        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation($products),
            'api_products_get_list',
            [],
            $page,
            $limit,
            $totalPages,
        );

        return new JsonResponse(
            $serializer->serialize($paginatedCollection, 'json', SerializationContext::create()->setGroups(['list', 'Default'])),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Détails d'un produit.
     *
     * @Route("/{id}", name="api_products_get_details", requirements={"id"="\d+"}, methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Détails d'un produit",
     *     @Model(type=Product::class, groups={"details"})
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="Le produit n'existe pas"
     * )
     *
     * @OA\Response(
     *     response=401,
     *     description="Le token est manquant ou n'est pas valide"
     * )
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="L'id du produit",
     *     @OA\Schema(type="integer")
     * )
     */
    public function getDetails(Product $product, SerializerInterface $serializer): JsonResponse
    {
        dd($product);

        return new JsonResponse(
            $serializer->serialize($product, 'json', SerializationContext::create()->setGroups(['details'])),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
