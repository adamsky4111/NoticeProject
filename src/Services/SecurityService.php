<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\SecurityServiceInterface;
use Symfony\Component\HttpFoundation\Request;
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

    public function saveUser(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        if($this->repository->checkIfExist($username)){
            return false;
        }
        $user = new User();
        $user->setEmail($username);
        $user->setPassword($this->encoder->encodePassword($user, $password));

        $this->repository->save($user);
        return true;
    }

}