<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class D_TrickFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger, private UserPasswordHasherInterface $passwordEncoder,){}

    public function load(ObjectManager $manager): void
    {
    $faker = Faker\Factory::create('fr_FR');

    for($j = 1; $j <= 10; $j++) {
        $trick = new Trick();
        $userId = $this->getReference('user'.rand(1, 7));
        $trick->setIdUser($userId);
        $categoryId = $this->getReference('category_' . rand(1, 5));
        $trick->setCategories($categoryId);
        $uniqueTitle = $faker->unique()->word();
        $trick->setTitle($uniqueTitle);
        $trick->setDescription($faker->text());
        $trick->setSlug($this->slugger->slug($trick->getTitle())->lower());
        $trick->setCreatedAt(new \DateTimeImmutable());
        $trick->setUpdatedAt(null);

        $manager->persist($trick);

        $this->addReference('trick'.$j, $trick);
    }
    $manager->flush();
    }
}