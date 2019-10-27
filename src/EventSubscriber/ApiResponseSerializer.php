<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Response\ApiResponse;
use App\Response\FormErrorsResponse;
use App\Service\Serializer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class ApiResponseSerializer implements EventSubscriberInterface
{
    private Serializer $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @see serializeView
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.view' => 'serializeView',
        ];
    }

    public function serializeView(ViewEvent $event): void
    {
        /** @var mixed $result */
        $result = $event->getControllerResult();
        if ($result instanceof FormErrorsResponse) {
            $response = $this->serializer->createFormErrorsResponse($result->getResponse());
            $event->setResponse($response);

            return;
        }
        if ($result instanceof ApiResponse) {
            $response = $this->serializer->createResponse($result->getData(), $result->getGroups(), $result->getStatus());
            $event->setResponse($response);
        }
    }
}
