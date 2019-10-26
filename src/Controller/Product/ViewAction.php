<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Product;
use App\Service\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{id}", methods={"GET"})
 */
class ViewAction
{
    private Serializer $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke(Product $product): JsonResponse
    {
        return $this->serializer->createResponse($product, ['product']);
    }
}