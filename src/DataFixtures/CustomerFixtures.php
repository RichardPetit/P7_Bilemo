<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;


class CustomerFixtures extends Fixture
{
//    private $passwordHasher;
//
//    public function __construct(PasswordHasherInterface $passwordHasher)
//    {
//        $this->passwordHasher = $passwordHasher;
//    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 10; $i++)
        {
            $customer = new Customer();
            $customer ->setName($faker->name)
                ->setEmail($faker->email)
                ->setPassword('password');


//            ->setPassword($this->passwordHasher->hash($faker->password()));

//            $customer->setPassword($this->passwordHasher->hashPassword($customer, '$faker->password()'));

            $manager->persist($customer);

        }
        $manager->flush();
    }
}
