<?php

namespace App\Repository\Custom;

use App\Entity\Offer;
use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository implements UserRepositoryInterface, PasswordUpgraderInterface
{
    private $repository;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function upgradePassword(UserInterface $user,
                                    string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', \get_class($user))
            );
        }

        $user->setPassword($newEncodedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function findOneByEmail($email)
    {
        $this->repository->findOneBy(['email' => $email]);
    }
}