<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory as Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->passwordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('admin@email.com')
            ->setUsername('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setIsActive(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
        ;
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'admin');
        $user->setPassword($hashedPassword);

        $manager->persist($user);


        // for ($i=0; $i < 50 ; $i++) { 
        //     $users
        // }

        $manager->flush();
    }
}
