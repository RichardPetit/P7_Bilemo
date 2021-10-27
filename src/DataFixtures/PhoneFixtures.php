<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PhoneFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 10; $i++)
        {
            $phone = new Phone();

            $phone->setName($faker->name)
                ->setDescription($faker->text)
                ->setBrand($faker->company)
                ->setColor($faker->colorName)
                ->setPrice($faker->numberBetween(99, 1500));
            $manager->persist($phone);
        }

        $manager->flush();
    }
}
