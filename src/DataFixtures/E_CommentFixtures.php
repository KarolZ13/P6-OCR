<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class E_CommentFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger, private UserPasswordHasherInterface $passwordEncoder,)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($k = 1; $k <= 30; $k++) {
            $comment = new Comment();
            $userId = $this->getReference('user' . rand(1, 7));
            $comment->setIdUser($userId);
            $trickId = $this->getReference('trick' . rand(1, 10));
            $comment->setIdTrick($trickId);
            $comment->setcontent($faker->text());
            $comment->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($comment);
        }
        $manager->flush();
    }
}
