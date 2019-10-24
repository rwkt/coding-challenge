<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\FormErrorsTransformer;
use App\Form\Type\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
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

    public function __construct(FormErrorsTransformer $formErrorsTransformer)
    {
        $this->formErrorsTransformer = $formErrorsTransformer;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function list(SerializerInterface $serializer, ProductRepository $repository): JsonResponse
    {
        $data = $serializer->serialize($repository->findAll(), 'json', SerializationContext::create()->setGroups(['product']));

        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function view(Product $product, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($product, 'json');

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

            return new JsonResponse([], Response::HTTP_CREATED);
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

            return new JsonResponse([], Response::HTTP_CREATED);
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
