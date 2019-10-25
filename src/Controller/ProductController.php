<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\FormErrorsTransformer;
use App\Form\Type\ProductType;
use App\Repository\ProductRepository;
use App\Service\Serializer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    private $formErrorsTransformer;
    private $serializer;

    public function __construct(FormErrorsTransformer $formErrorsTransformer, Serializer $serializer)
    {
        $this->formErrorsTransformer = $formErrorsTransformer;
        $this->serializer = $serializer;
    }

    /**
     * Duplicated because of bug in older Symfony versions, fixed in 4.1.
     *
     * @Route("", methods={"GET"})
     * @Route("/", methods={"GET"})
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
     * Duplicated because of bug in older Symfony versions, fixed in 4.1.
     *
     * @Route("", methods={"POST"})
     * @Route("/", methods={"POST"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $form = $this->createForm(ProductType::class);
        $form->submit($request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
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
     */
    public function update(Request $request, Product $product, EntityManagerInterface $em): JsonResponse
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($request->request->all());
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
