<?php

declare(strict_types=1);

namespace App\Response;

use Symfony\Component\Form\FormInterface;

class FormErrorsResponse
{
    private FormInterface $form;

    public function __construct(FormInterface $form)
    {
        $this->form = $form;
    }

    public function getResponse(): FormInterface
    {
        return $this->form;
    }
}
