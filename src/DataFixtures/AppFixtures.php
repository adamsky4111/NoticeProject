<?php

namespace App\DataFixtures;

use App\Entity\Notice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {

        for ($i = 0; $i < 50; $i++) {
            $notice = new Notice();
            $notice->setIsActive(1);
            $notice->setName('name '.$i);
            $notice->setProvince('province');
            $notice->setCity('city');
            $notice->setPrice(mt_rand(10000, 500000));
            $notice->setDescription('description '.$i );
            $notice->setImage('image '.$i );
            $notice->setAmount(mt_rand(1,100));
            $manager->persist($notice);
        }

        $manager->flush();
    }
}
