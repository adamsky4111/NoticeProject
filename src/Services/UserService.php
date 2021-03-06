<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService implements UserServiceInterface
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getUserByUsername($username)
    {
        return $this->repository->findUserByUsername($username);
    }

    public function getUserByEmail($email)
    {
        return $this->repository->findUserByEmail($email);
    }

    public function getUserById($id)
    {
        return $this->repository->findUserById($id);
    }

    public function getUsers(): array
    {
        return $this->repository->findUsers();
    }

    public function deleteUser($username)
    {
        if (!$user = $this->getUserByUsername($username)) {
            return false;
        };
        $this->repository->delete($user);

        return true;
    }

    public function saveUser(User $user)
    {
        $this->repository->save($user);
    }
}