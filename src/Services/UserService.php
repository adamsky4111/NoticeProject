<?php

namespace App\Services;

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
            throw new NotFoundHttpException('error, wrong user index');
        };
        $this->repository->delete($user);

        return true;
    }
}