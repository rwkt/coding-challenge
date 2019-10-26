<?php

declare(strict_types=1);

namespace App\ArgumentValueResolver;

use App\Annotation\Form;
use InvalidArgumentException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Generator;
use function sprintf;

class FormInterfaceResolver implements ArgumentValueResolverInterface
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();
        if (!$type) {
            return false;
        }

        return FormInterface::class === $type;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        /** @var Form|null $annotation */
        $annotation = $request->attributes->get('_form');
        if (!$annotation) {
            throw new InvalidArgumentException(sprintf('You must provide "%s" annotation to resolve typehinted FormInterface.', Form::class));
        }

        $class = $annotation->class;
        $dataParameter = $annotation->data;

        if ($dataParameter && !$request->attributes->has($dataParameter)) {
            throw new InvalidArgumentException(sprintf('Missing parameter "%s" in method signature.', $dataParameter));
        }
        $data = $dataParameter ? $request->attributes->get($dataParameter) : null;
        $form = $this->formFactory->create($class, $data);
        $form->submit($request->request->all());

        yield $form;
    }
}
