<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\SecurityServiceInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityService implements SecurityServiceInterface
{
    private $repository;
    private $encoder;

    public function __construct(UserRepositoryInterface $repository, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    public function saveUser($data)
    {
        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setPassword($this->encoder->encodePassword($user, $data['password']));
        $user->setIsActive(false);

        $this->repository->save($user);
        return true;
    }

    public function usernameExist($username): bool
    {
        return $this->repository->checkIfUsernameExist($username);
    }

    public function emailExist($email): bool
    {
        return $this->repository->checkifEmailExist($email);
    }

}