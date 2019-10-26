<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Annotation\Form;
use App\Form\Type\ProductType;
use App\Entity\Product;
use App\Form\FormErrorsTransformer;
use App\Service\Serializer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
    private FormErrorsTransformer $formErrorsTransformer;
    private EntityManagerInterface $em;

    public function __construct(Serializer $serializer, FormErrorsTransformer $formErrorsTransformer, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->formErrorsTransformer = $formErrorsTransformer;
        $this->em = $em;
    }

    public function __invoke(FormInterface $form): JsonResponse
    {
        if (!$form->isValid()) {
            return $this->formErrorsTransformer->createJsonResponse($form);

        }
        /** @var Product $product */
        $product = $form->getData();
        $this->em->persist($product);
        $this->em->flush();

        $data = $this->serializer->serialize($product, 'product');

        return JsonResponse::fromJsonString($data, Response::HTTP_CREATED);
    }
}
