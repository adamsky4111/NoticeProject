<?php

namespace App\Services\Interfaces;

use App\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface SecurityServiceInterface
{
    public function __construct(UserRepositoryInterface $repository, UserPasswordEncoderInterface $encoder);
    public function saveUser($data);
    public function usernameExist($username): bool;
}