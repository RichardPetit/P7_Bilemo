<?php

namespace App\DataFixtures;

use App\Entity\Customer;
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
            $userId = $i +1;

            $user->setFirstName($faker->firstName)
                ->setId($userId)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeThisYear()->format('d-m-Y H:i:s')))
                ->setPassword('password');

            $customer = new Customer();
            $customer ->setName($faker->name)
                ->setEmail($faker->email)
                ->setPassword('password')
                ->setUser($user);

            $manager->persist($user);
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
