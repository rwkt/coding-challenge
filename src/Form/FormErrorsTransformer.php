<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use function trim;

class FormErrorsTransformer
{
    public function createJsonResponse(FormInterface $form): JsonResponse
    {
        $errors = $this->fromForm($form);

        return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
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
