<?php


namespace App\Repository\Custom;


use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements UserRepositoryInterface
{

    private $repository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(User::class);
    }
    public function save(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function checkIfExist($username)
    {
        if(!($this->repository->findOneBy(['email' => $username]))) {
            return false;
        }
        return true;
    }

}