<?php

namespace App\Repository\Interfaces;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user);

    public function checkIfUsernameExist($username);

    public function checkIfEmailExist($email);
}