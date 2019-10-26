<?php

declare(strict_types=1);

namespace App\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

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
    public function serialize($data, string ...$groups): string
    {
        /** @var SerializationContext $context */
        $context = SerializationContext::create()->setGroups($groups);

        return $this->serializer->serialize($data, 'json', $context);
    }
}
