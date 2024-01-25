<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class F_PictureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($l = 1; $l <= 5; $l++) {
        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('180.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('360.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('aze.JPG');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('backflip.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('dashboard.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('dashboard.PNG');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('dashboard3.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('dashboard4.jpg');
        $manager->persist($picture);
        
        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('dashboard5.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('frontflip.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('indy.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('mute.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('nosegrab.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('noseslide.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('sad.jpg');
        $manager->persist($picture);

        $picture = new Picture();
        $trickId = $this->getReference('trick' . rand(1, 10));
        $picture->setTrick($trickId);
        $picture->setName('tailslide1.jpg');
        $manager->persist($picture);
        }

        $manager->flush();
    }
}