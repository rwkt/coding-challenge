<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Repository\ProductRepository;
use App\Service\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(methods={"GET"})
 */
class ListAction
{
    private Serializer $serializer;
    private ProductRepository $repository;

    public function __construct(Serializer $serializer, ProductRepository $repository)
    {
        $this->serializer = $serializer;
        $this->repository = $repository;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $pager = $this->repository->paginate($request->query->getInt('page', 1));

        return $this->serializer->createResponse($pager, ['product']);
    }
}
