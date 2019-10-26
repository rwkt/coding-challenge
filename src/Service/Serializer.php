<?php

declare(strict_types=1);

namespace App\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use function trim;

/**
 * Simplifies serialization to remove boilerplate code.
 */
class Serializer
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /** @param mixed $data */
    public function createResponse($data, array $groups, int $status = 200): JsonResponse
    {
        $string = $this->serialize($data, $groups);

        return JsonResponse::fromJsonString($string, $status);
    }

    public function createFormErrorsResponse(FormInterface $form): JsonResponse
    {
        $errors = $this->fromForm($form);

        return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /** @param mixed $data */
    public function serialize($data, array $groups): string
    {
        /** @var SerializationContext $context */
        $context = SerializationContext::create()->setGroups($groups);

        return $this->serializer->serialize($data, 'json', $context);
    }

    private function fromForm(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true, false) as $key => $error) {
            if ($error instanceof FormError) {
                $errors[] = $error->getMessage();
            } else {
                $errors[] = trim((string) $error);
            }
        }

        return $errors;
    }
}
