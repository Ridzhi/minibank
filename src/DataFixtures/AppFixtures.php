<?php

namespace App\DataFixtures;

use App\Entity\Balance;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = (new User())
            ->setName("User1")
            ->addBalance(
                (new Balance())
                    ->setTitle("Primary")
            );

        $user2 = (new User())
            ->setName("User2")
            ->addBalance(
                (new Balance())
                    ->setTitle("Primary")
            );

        $manager->persist($user1);
        $manager->persist($user2);

        $manager->flush();
    }
}
