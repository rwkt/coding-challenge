<?php

declare(strict_types=1);

namespace App\Controller\Category;

use App\Repository\CategoryRepository;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(methods={"GET"})
 */
class ListAction
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $pager = $this->repository->paginate($request->query->getInt('page', 1));

        return new ApiResponse($pager, ['category']);
    }
}
