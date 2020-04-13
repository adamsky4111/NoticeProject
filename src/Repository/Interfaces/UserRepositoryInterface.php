<?php

namespace App\Repository\Interfaces;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user);

    public function checkIfUsernameExist($username);

    public function checkIfEmailExist($email);

    public function findUserByUsername($username): User;

    public function findUserById($id): User;

    public function findUsers(): array;

    public function delete($username = null, $id = null, $email = null);
}