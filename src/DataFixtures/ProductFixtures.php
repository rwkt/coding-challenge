<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Generator;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as [$name, $sku, $price, $quantity, $categoryReference]) {
            /** @var Category $category */
            $category = $this->getReference($categoryReference);
            $product = new Product($name, $sku, $price, $category, $quantity);
            $manager->persist($product);
        }

        $manager->flush();
    }

    /** @return Generator<array{string, string, float, int, string}> */
    private function getData(): Generator
    {
        yield ['Pong', 'A0001', 69.99, 20, 'category_games'];
        yield ['GameStation 5', 'A0002', 269.99, 15, 'category_games'];
        yield ['AP Oman PC - Aluminum', 'A0003', 1399.99, 10, 'category_computers'];
        yield ['Fony UHD HDR 55" 4k TV', 'A0004', 1399.99, 5, 'category_accessories'];
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
