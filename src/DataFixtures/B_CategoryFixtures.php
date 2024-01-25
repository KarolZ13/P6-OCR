<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class B_CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Grabs');
        $category->setDescription('Un grab consiste à attraper la planche avec la main pendant le saut');
        $manager->persist($category);
        $this->addReference('category_1', $category);

        $category = new Category();
        $category->setName('Rotations');
        $category->setDescription('Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'atterrir en position switch ou normal');
        $manager->persist($category);
        $this->addReference('category_2', $category);

        $category = new Category();
        $category->setName('Flips');
        $category->setDescription('Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière');
        $manager->persist($category);
        $this->addReference('category_3', $category);

        $category = new Category();
        $category->setName('Rotation désaxées');
        $category->setDescription('Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation');
        $manager->persist($category);
        $this->addReference('category_4', $category);

        $category = new Category();
        $category->setName('Slides');
        $category->setDescription('Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l\'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé');
        $manager->persist($category);
        $this->addReference('category_5', $category);

        $manager->flush();
    }
}
