<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $microPost = new MicroPost();
            $microPost->setText('Some text goes here '.rand(0, 500));
            $microPost->setTime(new \DateTime('2019-02-06'));
            $manager->persist($microPost);
        }

        $manager->flush();
    }
}
