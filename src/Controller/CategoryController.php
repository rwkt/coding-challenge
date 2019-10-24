<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoryRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(SerializerInterface $serializer, CategoryRepository $repository): JsonResponse
    {
        $data = $serializer->serialize($repository->findAll(), 'json', SerializationContext::create()->setGroups(['category']));

        return JsonResponse::fromJsonString($data);
    }
}
