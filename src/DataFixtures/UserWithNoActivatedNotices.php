<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Notice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// activated user with notices, notices are not activated
class UserWithNoActivatedNotices extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('userWithNotices')
            ->setPassword($this->encoder->encodePassword($user, 'password'))
            ->setIsActive(true)
            ->setEmail('activatedWithNotices@email.email')
            ->setAccount(new Account());

        for ($i = 0; $i < 5; $i++) {
            $notice = new Notice();
            $notice->setName('name' . $i)
                ->setDescription('description, some text here ' . $i)
                ->setAccount($user->getAccount())
                ->setImages('image')
                ->setPrice(500)
                ->setAmount(5)
                ->setIsActive(false)
                ->setCity('city')
                ->setProvince('province');
            $manager->persist($notice);
        }

        $manager->persist($user);
        $manager->flush();
    }
}