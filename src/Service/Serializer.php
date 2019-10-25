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
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, string ...$groups): string
    {
        $context = SerializationContext::create()->setGroups($groups);

        return $this->serializer->serialize($data, 'json', $context);
    }
}
