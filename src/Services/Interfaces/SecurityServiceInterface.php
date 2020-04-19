<?php

namespace App\Services\Interfaces;

use App\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface SecurityServiceInterface
{
    public function __construct(UserRepositoryInterface $repository,
                                UserPasswordEncoderInterface $encoder,
                                AccountActivationInterface $accountActivation,
                                UserServiceInterface $userService,
                                RestorePasswordServiceInterface $restorePasswordService);

    public function saveUser($data);

    public function usernameExist($username): bool;

    public function emailExist($email): bool;

    public function changePassword($username, $newPassword): bool;

    public function resetPassword($email): bool;
}