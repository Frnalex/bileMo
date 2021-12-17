<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products")
 */
class ProductController
{
    /**
     * @Route(name="api_products_get_list", methods={"GET"})
     */
    public function getList(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation($productRepository->findAll()),
            'api_products_get_list', // route
            [], // route parameters
            1,       // page number
            5,      // limit
            3,       // total pages
            'page',  // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional, defaults to 'limit'
            false,   // generate relative URIs, optional, defaults to `false`
            75       // total collection size, optional, defaults to `null`
        );

        return new JsonResponse(
            $serializer->serialize($paginatedCollection, 'json', SerializationContext::create()->setGroups(['list'])),
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
