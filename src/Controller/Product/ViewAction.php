<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Product;
use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{id}", methods={"GET"})
 */
class ViewAction
{
    public function __invoke(Product $product): ApiResponse
    {
        return new ApiResponse($product, ['product']);
    }
}