<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{id}", methods={"DELETE"})
 * @IsGranted("ROLE_USER")
 */
class DeleteAction
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Product $product): JsonResponse
    {
        $this->repository->delete($product);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}