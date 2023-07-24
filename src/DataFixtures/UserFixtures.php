<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i<10; $i++) {
            $user = new User();

            $user->setEmail("user".$i."@example.com")
            ->setPassword($this->hasher->hashPassword($user, "test"))
            ->setRoles(array("ROLE_USER", "ROLE_ADMIN"))
            ->setLastName($faker->firstName())
            ->setFirstName($faker->lastName())
            ->setAddress($faker->streetAddress())
            ->setZipcode($faker->postcode());

            $manager->persist($user);
        }

        $manager->flush();
    }
}
