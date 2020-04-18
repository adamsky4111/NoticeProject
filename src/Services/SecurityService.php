<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AccountActivationInterface;
use App\Services\Interfaces\SecurityServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityService implements SecurityServiceInterface
{
    private $repository;
    private $encoder;
    private $accountActivation;
    private $userService;

    public function __construct(UserRepositoryInterface $repository,
                                UserPasswordEncoderInterface $encoder,
                                AccountActivationInterface $accountActivation,
                                UserServiceInterface $userService)
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
        $this->accountActivation = $accountActivation;
        $this->userService = $userService;
    }

    public function saveUser($data)
    {
        $activationCode = $this->accountActivation->createUniqueKey();

        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setPassword($this->encoder->encodePassword($user, $data['password']));
        $user->setIsActive(false);
        $user->setActivationCode($activationCode);
        $this->repository->save($user);
        $userId = $user->getId();

        $this->accountActivation->sendAccountActivationUrl(
            $data['email'],
            $userId,
            $activationCode,
            $data['username']
        );

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

    public function changePassword($username, $newPassword)
    {
        /**
         * @var User $user
         */
        $user = $this->userService->getUserByUsername($username);

        ($user->setPassword($this->encoder->encodePassword($user, $newPassword)) ? $bool = true : $bool = false);

        return $bool;
    }
}

