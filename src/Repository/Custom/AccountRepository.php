<?php

namespace App\Repository\Custom;

use App\Entity\Account;
use App\Repository\Interfaces\AccountRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class AccountRepository implements AccountRepositoryInterface
{
    private $entityManager;

    private $accountRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->accountRepository = $this->entityManager->getRepository(Account::class);
    }

    public function saveAccountChanges(Account $account)
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }

    public function findAll()
    {
        return $this->accountRepository->findAll();
    }

    public function findOneById($id)
    {
        return $this->accountRepository->findBy(['id' => $id]);
    }
}