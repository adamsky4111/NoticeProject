<?php

namespace App\Repository\Interfaces;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;

interface AccountRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager);

    public function saveAccountChanges(Account $account);

    public function findAll();

    public function findOneById($id);
}