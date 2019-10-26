<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\Form;
use App\Entity\Product;
use App\Form\FormErrorsTransformer;
use App\Form\Type\ProductType;
use App\Repository\ProductRepository;
use App\Service\Serializer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ProductController extends AbstractController
{
    private FormErrorsTransformer $formErrorsTransformer;
    private Serializer $serializer;

    public function __construct(FormErrorsTransformer $formErrorsTransformer, Serializer $serializer)
    {
        $this->formErrorsTransformer = $formErrorsTransformer;
        $this->serializer = $serializer;
    }

    /**
     * @Route(methods={"GET"})
     */
    public function list(ProductRepository $repository, Request $request): JsonResponse
    {
        $pager = $repository->paginate($request->query->getInt('page', 1));
        $data = $this->serializer->serialize($pager, 'product');

        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function view(Product $product): JsonResponse
    {
        $data = $this->serializer->serialize($product, 'product');

        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route(methods={"POST"})
     *
     * @IsGranted("ROLE_USER")
     *
     * @Form(class=ProductType::class)
     */
    public function create(FormInterface $form, EntityManagerInterface $em): JsonResponse
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            $em->persist($product);
            $em->flush();

            $data = $this->serializer->serialize($product, 'product');

            return JsonResponse::fromJsonString($data, Response::HTTP_CREATED);
        }

        $errors = $this->formErrorsTransformer->fromForm($form);

        return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", methods={"PUT", "PATCH"})
     *
     * @IsGranted("ROLE_USER")
     * @Form(class=ProductType::class, data="product")
     */
    public function update(FormInterface $form, Product $product, EntityManagerInterface $em): JsonResponse
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            $data = $this->serializer->serialize($product, 'product');

            return JsonResponse::fromJsonString($data);
        }

        $errors = $this->formErrorsTransformer->fromForm($form);

        return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function delete(Product $product, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($product);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
