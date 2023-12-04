<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger, private UserPasswordHasherInterface $passwordEncoder,){}

    public function load(ObjectManager $manager): void
    {
    $faker = Faker\Factory::create('fr_FR');

    for($i = 1; $i <= 7; $i++){
        $user = new User();
        $user->setEmail($faker->email);
        $user->setRoles([]);
        $user->setPassword(
            $this->passwordEncoder->hashPassword($user, 'secret')
        );
        $user->setUsername($faker->username);
        $user->setAvatar($faker->image(null, 640, 480));
        $user->setIsVerified('1');
        
        $manager->persist($user);
        
        $this->addReference('user'.$i, $user);
    }

    $manager->flush();

    for($j = 1; $j <= 10; $j++) {
        $trick = new Trick();
        $userId = $this->getReference('user'.rand(1, 7));
        $trick->setIdUser($userId);
        $categoryId = $this->getReference('category_' . rand(1, 5));
        $trick->setIdCategories($categoryId);
        $trick->setTitle($faker->word());
        $trick->setDescription($faker->text());
        $trick->setSlug($this->slugger->slug($trick->getTitle())->lower());
        $trick->setCreatedAt(new \DateTimeImmutable());
        $trick->setUpdatedAt(null);

        $manager->persist($trick);

        $this->addReference('trick'.$j, $trick);
    }

    $manager->flush();

    for($k = 1; $k <= 30; $k++) {
        $comment = new Comment();
        $userId = $this->getReference('user'.rand(1, 7));
        $comment->setIdUser($userId);
        $trickId = $this->getReference('trick' . rand(1, 10));
        $comment->setIdTrick($trickId);
        $comment->setcontent($faker->text());
        $comment->setCreatedAt(new \DateTimeImmutable());

        $manager->persist($comment);
    }
    $manager->flush();

    for($l = 1; $l <= 20; $l++) {
        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName($faker->image(null, 640, 480));
        $manager->persist($picture);
    }
    $manager->flush();

    }
}