<?php

declare(strict_types=1);

namespace App\Repository\Model;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Use this trait instead of another extends, at least until doctrine-extensions are fixed.
 *
 * @method QueryBuilder createQueryBuilder($alias)
 */
trait RepositoryUtilsTrait
{
    public function paginate(int $page): Pagerfanta
    {
        $qb = $this->createQueryBuilder('o');
        $adapter = new DoctrineORMAdapter($qb);

        $pager = new Pagerfanta($adapter);
        $pager->setNormalizeOutOfRangePages(true);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
