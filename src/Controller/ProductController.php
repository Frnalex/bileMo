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
     *     response=200,
     *     description="Liste des produits",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"list"}))
     *     )
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
        $totalPages = ceil($productRepository->getTotalRows() / $limit);

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
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="L'id du produit",
     *     @OA\Schema(type="integer")
     * )
     */
    public function getDetails(Product $product, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($product, 'json', SerializationContext::create()->setGroups(['details'])),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
