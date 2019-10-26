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
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("", methods={"POST"})
 * @IsGranted("ROLE_USER")
 *
 * @Form(class=ProductType::class)
 */
class CreateAction
{
    private Serializer $serializer;
    private EntityManagerInterface $em;

    public function __construct(Serializer $serializer, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    public function __invoke(FormInterface $form)
    {
        if (!$form->isValid()) {
            return $this->serializer->createFormErrorsResponse($form);

        }
        /** @var Product $product */
        $product = $form->getData();
        $this->em->persist($product);
        $this->em->flush();

        return $this->serializer->createResponse($product, ['product'], 201);
    }
}
