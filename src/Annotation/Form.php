<?php

declare(strict_types=1);

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 *
 * @Target("METHOD")
 */
class Form implements ConfigurationInterface
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @required
     */
    public string $class;

    /** @psalm-suppress PropertyNotSetInConstructor */
    public ?string $data = null;

    public function getAliasName(): string
    {
        return 'form';
    }

    public function allowArray(): bool
    {
        return false;
    }
}
