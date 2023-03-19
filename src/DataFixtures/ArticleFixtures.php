<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();


        $category = $manager->getRepository(Category::class)->findAll();


        for ($i = 0; $i < 100; $i++) {
            $article = new Article();

            $article
                ->setTitle($faker->sentence)
                ->setContent($faker->text)
                ->setAuthor($this->getReference(UserFixtures::AUTHOR_USER_REFERENCE))
                ->setSlug($faker->sentence)
                ->setCategory($faker->randomElement($category))
                ->setStatus($faker->numberBetween(1, 4))
                ->setCreatedAt($faker->dateTimeThisMonth)
                ->setUpdatedAt($faker->dateTimeThisMonth);

            $manager->persist($article);
        }

        $manager->flush();


    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}