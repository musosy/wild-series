<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends Fixture
{
    const CATEGORIES_LIST = [
        'Action',
        'Aventure',
        'Animation',
        'Fantastique',
        'Horreur',
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES_LIST as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference('category' .($key+1), $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
