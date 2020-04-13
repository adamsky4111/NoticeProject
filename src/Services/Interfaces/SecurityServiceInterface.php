<?php

namespace App\Services\Interfaces;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface SecurityServiceInterface
{
    public function __construct(UserRepositoryInterface $repository, UserPasswordEncoderInterface $encoder);

    public function saveUser($data);

    public function usernameExist($username): bool;

    public function emailExist($email): bool;

    public function getUserByUsername($username): User;

    public function getUserById($id): User;

    public function getUsers(): array;

    public function deleteUser($username = null, $id = null, $email = null);
}