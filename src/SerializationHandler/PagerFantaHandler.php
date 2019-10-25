<?php

declare(strict_types=1);

namespace App\SerializationHandler;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;
use Pagerfanta\Pagerfanta;
use Traversable;
use function iterator_to_array;

class PagerFantaHandler implements SubscribingHandlerInterface
{
    /**
     * @see serializePagerFanta
     */
    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => Pagerfanta::class,
                'method' => 'serializePagerFanta',
            ],
        ];
    }

    public function serializePagerFanta(JsonSerializationVisitor $visitor, Pagerfanta $pager, array $type, SerializationContext $context): array
    {
        $results = $pager->getCurrentPageResults();
        if ($results instanceof Traversable) {
            $results = iterator_to_array($results, false);
        }

        return [
            'total' => $pager->count(),
            'nr_of_results_per_page' => $pager->getMaxPerPage(),
            'current_page' => $pager->getCurrentPage(),
            'nr_of_pages' => $pager->getNbPages(),
            'prev_page' => $pager->hasPreviousPage() ? $pager->getPreviousPage() : null,
            'next_page' => $pager->hasNextPage() ? $pager->getNextPage() : null,
            'results' => $visitor->visitArray($results, $type),
        ];
    }
}
