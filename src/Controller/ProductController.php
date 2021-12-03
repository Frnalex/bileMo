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
     * @Route(name="api_products_collection_get", methods={"GET"})
     */
    public function collection(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($productRepository->findAll(), 'json', ['groups' => 'getAll']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_products_item_get", methods={"GET"})
     */
    public function item(Product $product, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($product, 'json', ['groups' => 'getItem']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
