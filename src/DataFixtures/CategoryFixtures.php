<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Generator;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as [$name, $reference]) {
            $category = new Category($name);
            $this->addReference($reference, $category);
            $manager->persist($category);
        }

        $manager->flush();
    }

    private function getData(): Generator
    {
        yield ['Games', 'category_games'];
        yield ['Computers', 'category_computers'];
        yield ['TVs and Accessories', 'category_accessories'];
    }
}
