<?php

namespace App\Services;

use App\Entity\Account;
use App\Repository\Interfaces\AccountRepositoryInterface;
use App\Services\Interfaces\AccountManagementInterface;

class AccountManagament implements AccountManagementInterface
{
    private $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function updateAccount($data, Account $account)
    {
        if ($data['firstName'] !== null) {
            $account->setFirstName($data['firstName']);
        }
        if ($data['lastName'] !== null) {
            $account->setLastName($data['lastName']);
        }
        if ($data['province'] !== null) {
            $account->setProvince($data['province']);
        }
        if ($data['city'] !== null) {
            $account->setCity($data['city']);
        }
        if ($data['phone'] !== null) {
            $account->setPhone($data['phone']);
        }

        $this->accountRepository->saveAccountChanges($account);
    }
}