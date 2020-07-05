<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

//users for access controll tests
class UsersForAuthorize extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $activatedUser = new User();

        $activatedUser->setUsername('username')
            ->setPassword($this->encoder->encodePassword($activatedUser, 'password'))
            ->setIsActive(true)
            ->setEmail('email@email.email')
            ->setAccount(new Account());

        $manager->persist($activatedUser);

        $notActivatedUser = new User();

        $notActivatedUser->setUsername('username2')
            ->setPassword($this->encoder->encodePassword($notActivatedUser, 'password'))
            ->setIsActive(false)
            ->setEmail('2email@email.email');

        $manager->persist($notActivatedUser);

        $manager->flush();
    }
}
