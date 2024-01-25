<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class C_UserFixtures extends Fixture
{
    private $filesystem;

    public function __construct(private SluggerInterface $slugger, private UserPasswordHasherInterface $passwordEncoder, Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function load(ObjectManager $manager): void
    {
    $faker = Faker\Factory::create('fr_FR');

    for($i = 1; $i <= 7; $i++){
        $user = new User();
        $username = $faker->username;
        $user->setUsername($username);
        $user->setEmail($faker->email);
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword(
            $this->passwordEncoder->hashPassword($user, $username)
        );
        
        $user->setIsVerified('1');

        $manager->persist($user);
        
        $this->addReference('user'.$i, $user);
    }

    $manager->flush();
    }
}