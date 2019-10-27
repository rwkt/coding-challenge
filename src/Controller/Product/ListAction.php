<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Repository\ProductRepository;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(methods={"GET"})
 */
class ListAction
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $pager = $this->repository->paginate($request->query->getInt('page', 1));

        return new ApiResponse($pager, ['product']);
    }
}
