<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/products")
 */
class ProductController
{
    /**
     * @Route(name="api_products_get_all", methods={"GET"})
     */
    public function getAll(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($productRepository->findAll(), 'json', ['groups' => 'getAll']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_products_get_item", methods={"GET"})
     */
    public function getItem(Product $product, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($product, 'json', ['groups' => 'getItem']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
