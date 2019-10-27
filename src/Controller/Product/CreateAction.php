<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Annotation\Form;
use App\Form\Type\ProductType;
use App\Entity\Product;
use App\Response\ApiResponse;
use App\Response\FormErrorsResponse;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("", methods={"POST"})
 * @IsGranted("ROLE_USER")
 *
 * @Form(class=ProductType::class)
 */
class CreateAction
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(FormInterface $form)
    {
        if (!$form->isValid()) {
            return new FormErrorsResponse($form);

        }
        /** @var Product $product */
        $product = $form->getData();
        $this->em->persist($product);
        $this->em->flush();

        return new ApiResponse($product, ['product'], 201);
    }
}
