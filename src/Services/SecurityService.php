<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AccountActivatorInterface;
use App\Services\Interfaces\RestorePasswordServiceInterface;
use App\Services\Interfaces\SecurityServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityService implements SecurityServiceInterface
{
    private $repository;

    private $encoder;

    private $accountActivator;

    private $userService;

    private $restorePasswordService;

    public function __construct(UserRepositoryInterface $repository,
                                UserPasswordEncoderInterface $encoder,
                                AccountActivatorInterface $accountActivator,
                                UserServiceInterface $userService,
                                RestorePasswordServiceInterface $restorePasswordService)
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
        $this->accountActivator = $accountActivator;
        $this->userService = $userService;
        $this->restorePasswordService = $restorePasswordService;
    }

    public function saveUser($data)
    {
        $activationCode = $this->accountActivator->createUniqueKey();

        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setPassword($this->encoder->encodePassword($user, $data['password']));
        $user->setIsActive(false);
        $user->setActivationCode($activationCode);
        $this->userService->saveUser($user);
        $userId = $user->getId();

        $this->accountActivator->sendAccountActivationUrl(
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

    public function changePassword($username, $newPassword): bool
    {
        /**
         * @var User $user
         */
        $user = $this->userService->getUserByUsername($username);

        ($user->setPassword($this->encoder->encodePassword($user, $newPassword)) ? $bool = true : $bool = false);

        return $bool;
    }

    public function resetPassword($email): bool
    {
        $user = $this->userService->getUserByEmail($email);

        if ($user === null) {
            return false;
        }

        $addressEmail = $user->getEmail();
        $username = $user->getUsername();
        $newPassword = $this->restorePasswordService->sendAndGenerateNewPassword($addressEmail, $username);

        $this->changePassword($username, $newPassword);

        return true;
    }

    public function activateUser($activationCode, $userId)
    {
        /**
         * @var User $user
         */
        $user = $this->userService->getUserById($userId);

        $isValid = $this->accountActivator->isCodeValid($activationCode, $user);

        if ($isValid !== false) {
            $user->setIsActive(true);
            $this->userService->saveUser($user);
            return true;
        }

        return false;
    }
}

