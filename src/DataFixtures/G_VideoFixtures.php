<?php

namespace App\DataFixtures;

use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class G_VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($m = 1; $m <= 5; $m++) {
            $video = new Video();
            $trickId = $this->getReference('trick' . rand(1, 10));
            $video->setTrick($trickId);
            $video->setName('https://www.youtube.com/watch?v=CA5bURVJ5zk');
            $manager->persist($video);

            $video = new Video();
            $trickId = $this->getReference('trick' . rand(1, 10));
            $video->setTrick($trickId);
            $video->setName('https://www.youtube.com/watch?v=lunYxCQrs1E');
            $manager->persist($video);

            $video = new Video();
            $trickId = $this->getReference('trick' . rand(1, 10));
            $video->setTrick($trickId);
            $video->setName('https://www.youtube.com/watch?v=L4bIunv8fHM');
            $manager->persist($video);

            $video = new Video();
            $trickId = $this->getReference('trick' . rand(1, 10));
            $video->setTrick($trickId);
            $video->setName('https://www.youtube.com/watch?v=Iofrv4rxJcY');
            $manager->persist($video);

            $video = new Video();
            $trickId = $this->getReference('trick' . rand(1, 10));
            $video->setTrick($trickId);
            $video->setName('https://www.youtube.com/watch?v=OMCqVJ8rmyQ&t=4s');
            $manager->persist($video);

            $video = new Video();
            $trickId = $this->getReference('trick' . rand(1, 10));
            $video->setTrick($trickId);
            $video->setName('https://www.youtube.com/watch?v=ivO_fl0HrXs');
            $manager->persist($video);

            $video = new Video();
            $trickId = $this->getReference('trick' . rand(1, 10));
            $video->setTrick($trickId);
            $video->setName('https://www.youtube.com/watch?v=xGG56MWgbOA');
            $manager->persist($video);
        }

        $manager->flush();
    }
}
