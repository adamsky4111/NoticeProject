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

    public function checkIfUsernameExist($username)
    {
        if ($this->repository->findOneBy(['username' => $username])) {
            return true;
        }
        return false;
    }

    public function checkIfEmailExist($email)
    {
        if ($this->repository->findOneBy(['email' => $email])) {
            return true;
        }
        return false;
    }

    public function findUserByUsername($username)
    {
        return $this->repository->findOneBy(['username' => $username]);
    }

    public function findUserById($id)
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function findUsers(): array
    {
        return $this->repository->findAll();
    }

    public function delete($user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}