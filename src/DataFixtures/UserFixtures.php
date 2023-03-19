<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory as Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->passwordHasher = $userPasswordHasher;
    }

    public const AUTHOR_USER_REFERENCE = 'author-user';
    public const USER_USER_REFERENCE = 'author-user-user';

    public function load(ObjectManager $manager)
    {
        $faker = Faker::create('fr_FR');

        for ($i = 0; $i < 48; $i++) {
            $user = new User();
            
            $user
                ->setEmail($faker->email())
                ->setUsername($faker->userName)
                ->setRoles(['ROLE_USER'])
                ->setCreatedAt($faker->dateTimeThisMonth)
                ->setUpdatedAt($faker->dateTimeThisMonth);

            $hashedPassword = $this->passwordHasher->hashPassword($user, 'user');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

            $admin = new User();

            $admin
                ->setEmail($faker->email())
                ->setUsername($faker->userName)
                ->setRoles(['ROLE_ADMIN'])
                ->setUpdatedAt($faker->dateTimeThisMonth)
                ->setCreatedAt($faker->dateTimeThisMonth);

            $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
            $admin->setPassword($hashedPassword);

            $manager->persist($admin);

            $author = new User();

            $author
                ->setEmail($faker->email())
                ->setUsername($faker->userName)
                ->setRoles(['ROLE_AUTHOR'])
                ->setCreatedAt($faker->dateTimeThisMonth)
                ->setUpdatedAt($faker->dateTimeThisMonth);

            $hashedPassword = $this->passwordHasher->hashPassword($author, 'author');
            $author->setPassword($hashedPassword);

            $manager->persist($author);

        $manager->flush();
        $this->addReference(self::USER_USER_REFERENCE, $user);
        $this->addReference(self::AUTHOR_USER_REFERENCE, $author);
    }
}
