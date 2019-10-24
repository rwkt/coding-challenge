<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use function trim;

class FormErrorsTransformer
{
    public function fromForm(FormInterface $form): array
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
