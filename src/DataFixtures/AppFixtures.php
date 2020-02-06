<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {

        for ($i = 0; $i < 50; $i++) {
            $offer = new Offer();
            $offer->setIsActive(1);
            $offer->setName('name '.$i);
            $offer->setSize(mt_rand(10, 100));
            $offer->setArea(mt_rand(100, 500));
            $offer->setPrice(mt_rand(10000, 500000));
            $offer->setDescription('description '.$i );
            $offer->setImage('image '.$i );
            $manager->persist($offer);
        }

        $manager->flush();
    }
}
