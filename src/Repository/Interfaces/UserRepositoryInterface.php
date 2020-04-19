<?php

namespace App\Repository\Interfaces;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user);

    public function checkIfUsernameExist($username);

    public function checkIfEmailExist($email);

    public function findUserByUsername($username);

    public function findUserByEmail($email);

    public function findUserById($id);

    public function findUsers(): array;

    public function delete($username);
}