<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Annotation\Form;
use App\Form\Type\ProductType;
use App\Entity\Product;
use App\Service\Serializer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{id}", methods={"PUT", "PATCH"})
 * @IsGranted("ROLE_USER")
 *
 * @Form(class=ProductType::class, data="product")
 */
class UpdateAction
{
    private Serializer $serializer;
    private EntityManagerInterface $em;

    public function __construct(Serializer $serializer, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    public function __invoke(FormInterface $form, Product $product): JsonResponse
    {
        if (!$form->isValid()) {
            return $this->serializer->createFormErrorsResponse($form);
        }

        $this->em->persist($product);
        $this->em->flush();

        return $this->serializer->createResponse($product, ['product']);
    }
}
