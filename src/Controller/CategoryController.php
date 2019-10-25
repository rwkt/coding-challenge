<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoryRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function index(Request $request, CategoryRepository $repository): JsonResponse
    {
        $pager = $repository->paginate($request->query->getInt('page', 1));
        $data = $this->serializer->serialize($pager, 'json', SerializationContext::create()->setGroups(['category']));

        return JsonResponse::fromJsonString($data);
    }
}
