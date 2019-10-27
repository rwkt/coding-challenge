<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Annotation\Form;
use App\Form\Type\ProductType;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Response\ApiResponse;
use App\Response\FormErrorsResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{id}", methods={"PUT", "PATCH"})
 * @IsGranted("ROLE_USER")
 *
 * @Form(class=ProductType::class, data="product")
 */
class UpdateAction
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @param FormInterface<Product> $form */
    public function __invoke(FormInterface $form, Product $product)
    {
        if (!$form->isValid()) {
            return new FormErrorsResponse($form);
        }
        $this->repository->save($product);

        return new ApiResponse($product, ['product']);
    }
}
