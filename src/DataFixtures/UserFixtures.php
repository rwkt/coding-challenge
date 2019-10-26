<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Generator;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as [$name, $email, $apiToken]) {
            $user = new User($name, $email);
            $user->setApiToken($apiToken);
            $manager->persist($user);
        }
        $manager->flush();
    }

    /** @return Generator<array{string, string, string}> */
    private function getData(): Generator
    {
        yield ['Test Foo', 'foo-test@foo.com', 'foo_token'];
        yield ['Test Bar', 'bar-test@bar.com', 'bar_token'];
    }
}
