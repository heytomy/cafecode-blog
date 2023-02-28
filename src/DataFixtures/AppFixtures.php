<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
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
        $faker = Faker::create();

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

        $users = [];

        for ($i=0; $i < 48; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->email())
                ->setUsername($faker->username())
                ->setRoles(['ROLE_USER'])
                ->setIsActive(true)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
            ;
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();

        for ($i=0; $i < 10; $i++) { 
            $category = new Category();
            $category
            ->setName($faker->word(1, true))
            ->setColor($faker->hexColor)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt($category->getCreatedAt())
            ;

            $categories[] = $category;
            $manager->persist($category);
        }

        $manager->flush();

        for ($i=0; $i < 100 ; $i++) { 
            $title = $faker->words(3, true);
            $slug = strtolower(str_replace(' ', '-', $title));

            $indexAleatoire = array_rand($users);
            $userAleatoire = $users[$indexAleatoire];
            $article = new Article();

            $article
            ->setTitle($title)
            ->setContent($faker->sentences(7, true))
            ->setSlug($slug)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(($article->getCreatedAt()))
            ->setStatus($faker->numberBetween(0, 1))
            ->setFeaturedImage($faker->imageUrl())
            ->setAuthor($userAleatoire)
            ->setCategory($category)
            ;

            $manager->persist($article);
        }

        $manager->flush();
    }
}