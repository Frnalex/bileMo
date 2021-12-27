<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products")
 */
class ProductController
{
    /**
     * @Route(name="api_products_get_list", methods={"GET"})
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
     * @Route("/{id}", name="api_products_get_details", methods={"GET"})
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
