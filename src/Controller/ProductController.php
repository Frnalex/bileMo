<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
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
        return new JsonResponse(
            $serializer->serialize($productRepository->findAll(), 'json', SerializationContext::create()->setGroups(['list'])),
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
