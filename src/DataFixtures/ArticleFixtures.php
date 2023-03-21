<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Cocur\Slugify\Slugify;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();


        $category = $manager->getRepository(Category::class)->findAll();


        for ($i = 0; $i < 100; $i++) {
            $article = new Article();

            $slugify = new Slugify(["regexp" => "/([^A-Za-z0-9]|-)+/"]);

            $article
                ->setTitle($faker->sentence())
                ->setContent($faker->paragraph(10))
                ->setAuthor($this->getReference(UserFixtures::AUTHOR_USER_REFERENCE))
                ->setSlug($slugify->slugify($article->getTitle()))
                ->setCategory($faker->randomElement($category))
                ->setStatus($faker->numberBetween(1, 4))
                ->setCreatedAt($faker->dateTimeThisMonth)
                ->setUpdatedAt($faker->dateTimeThisMonth)
                ->setFeaturedImage($faker->imageUrl(640, 200, 'article', true));

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