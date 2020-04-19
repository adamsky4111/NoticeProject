<?php


namespace App\Services\Interfaces;

use App\Repository\Interfaces\UserRepositoryInterface;

interface UserServiceInterface
{
    public function __construct(UserRepositoryInterface $repository);

    public function getUserByUsername($username);

    public function getUserByEmail($email);

    public function getUserById($id);

    public function getUsers(): array;

    public function deleteUser($username);
}