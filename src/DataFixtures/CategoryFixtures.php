<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Action',
        'Aventure',
        'Drame',
        'Biopic',
        'Déjanté',
        'Fantastique',
        'Surnaturel',
        'Science-fiction',
        'Horreur',
        'Animation',
        'Thriller psychologique',
    ];

    public function load(ObjectManager $manager): void
    {

        foreach (self::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
            $this->addReference('category_' .$key, $category);
        }

        $manager->flush();
    }
}
