<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 10; $i++)
        {
            $user = new User();

            $user->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setEmail($faker->email)
            ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeThisYear()->format('d-m-Y H:i:s')));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
